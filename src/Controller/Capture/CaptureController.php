<?php

namespace App\Controller\Capture;

use App\Entity\Capture\Capture;
use App\Entity\Capture\CaptureTemplate;
use App\Form\Capture\CaptureElement\CaptureElementInternalForm;
use App\Form\Capture\CaptureInternalForm;
use App\Repository\CaptureRepository;
use App\Service\ConditionToggler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/capture')]
final class CaptureController extends AbstractController
{
    #[Route(name: 'app_capture_index', methods: ['GET'])]
    public function index(CaptureRepository $captureRepository): Response
    {
        $all = $captureRepository->findAll();
        $templates = array_filter($all, fn($el) => !$el->isTemplate());

        return $this->render('capture/index.html.twig', [
            'captures' => $all,
        ]);
    }

    #[Route('/new', name: 'app_capture_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $capture = new Capture();
        $form = $this->createForm(CaptureInternalForm::class, $capture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($capture);
            $entityManager->flush();

            return $this->redirectToRoute('app_capture_edit', ['id' => $capture->getId(),], Response::HTTP_SEE_OTHER);
        }

        return $this->render('capture/new.html.twig', [
            'capture' => $capture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'app_capture_show', methods: ['GET'])]
    public function show(Capture $capture): Response
    {
        return $this->render('capture/show.html.twig', [
            'capture' => $capture,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_capture_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Capture $capture, EntityManagerInterface $entityManager, ConditionToggler $toggler): Response
    {

        //apply toggle activation from conditions
        $conditions = $capture->getConditions();
        $toggler->apply(is_iterable($conditions) ? $conditions : []);

        //make condition map for display condition on CaptureElement
        $conditionsByTargetId = [];
        foreach ($capture->getConditions() as $cond) {
            $tid = $cond->getTargetElement()?->getId();
            if ($tid !== null) {
                $conditionsByTargetId[$tid][] = $cond;
            }
        }

        $forms = [];
        foreach ($capture->getCaptureElements() as $el) {
            $forms[$el->getId()] = $this->createForm(CaptureElementInternalForm::class, $el, [
                'action' => $this->generateUrl('app_capture_element_respond', [
                    'id' => $el->getId(), 'captureId' => $capture->getId()
                ]),
                'method' => 'POST',
                'disabled' => !($el->isActive()),
                'attr' => ['id' => $el->getId()],
            ])->createView();
        }
        return $this->render('capture/edit.html.twig', [
            'capture' => $capture,
            'forms' => $forms,
            'conditionsByTargetId' => $conditionsByTargetId,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_capture_delete', methods: ['POST'])]
    public function delete(Request $request, Capture $capture, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $capture->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($capture);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_capture_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/render-preview', name: 'app_capture_render_text_preview', methods: ['GET'])]
    public function renderPreview(Capture $capture): Response
    {
        return $this->render('capture/render_preview.html.twig', [
            'capture' => $capture,
        ]);
    }

    #[Route('/capture/{id}/clone', name: 'app_capture_clone', methods: ['GET'])]
    public function cloneFromTemplate(CaptureTemplate $template, EntityManagerInterface $em): Response
    {
        // 1) Create child entity (JOINED)
        $capture = new Capture();

        // Copy scalars (Same Model)
        $this->copyIf($template, $capture, 'Name');
        $this->copyIf($template, $capture, 'Description');

        // Persist early to ensure joined rows exist; avoids edge-cases with proxies
        $em->persist($capture);
        $em->flush();

        // 2) Clone one-to-one Title (if any)
        if (method_exists($template, 'getTitle') && $template->getTitle()) {
            $titleClone = clone $template->getTitle();
            if (method_exists($titleClone, 'setCapture')) {
                $titleClone->setCapture($capture);
            }
            if (method_exists($capture, 'setTitle')) {
                $capture->setTitle($titleClone);
            }
            $em->persist($titleClone);
        }

        // 3) First pass: clone elements (once), reuse their already-cloned children
        $elMap = new \SplObjectStorage();    // original element => cloned element
        $fieldMap = new \SplObjectStorage(); // original field   => cloned field

        foreach ($template->getCaptureElements() as $tplEl) {
            // Clone element ONCE
            $el = clone $tplEl;

            // Re-attach owning side to the new Capture
            if (method_exists($el, 'setCapture')) {
                $el->setCapture($capture);
            }
            if (method_exists($capture, 'addCaptureElement')) {
                $capture->addCaptureElement($el);
            }
            $em->persist($el);

            // Map original element -> cloned element
            $elMap[$tplEl] = $el;

            // Pair original fields with cloned fields by iteration order (stable if you already order by position)
            $origFields = is_iterable($tplEl->getFields()) ? array_values(is_array($tplEl->getFields()) ? $tplEl->getFields() : $tplEl->getFields()->toArray()) : [];
            $clonedFields = is_iterable($el->getFields()) ? array_values(is_array($el->getFields()) ? $el->getFields() : $el->getFields()->toArray()) : [];

            $count = min(count($origFields), count($clonedFields));
            for ($i = 0; $i < $count; $i++) {
                $origField = $origFields[$i];
                $clonedField = $clonedFields[$i];

                // Ensure owning side points to cloned element
                if (method_exists($clonedField, 'setElement')) {
                    $clonedField->setElement($el);
                }
                $em->persist($clonedField);

                // Map original field -> cloned field (object identity)
                $fieldMap[$origField] = $clonedField;
            }

            // Persist cloned calculated variables (do NOT re-clone from template)
            if (method_exists($el, 'getCalculatedvariables')) {
                foreach ($el->getCalculatedvariables() as $cv) {
                    if (method_exists($cv, 'setElement')) {
                        $cv->setElement($el);
                    }
                    $em->persist($cv);
                }
            }
        }

        // 4) Second pass: clone and remap conditions using object-identity maps
        if (method_exists($template, 'getConditions')) {
            foreach ($template->getConditions() as $tplCond) {
                $cond = clone $tplCond;

                if (method_exists($cond, 'setCapture')) {
                    $cond->setCapture($capture);
                }

                // Remap element references via elMap (do not use IDs)
                if (method_exists($tplCond, 'getSourceElement') && $tplCond->getSourceElement() && isset($elMap[$tplCond->getSourceElement()]) && method_exists($cond, 'setSourceElement')) {
                    $cond->setSourceElement($elMap[$tplCond->getSourceElement()]);
                }
                if (method_exists($tplCond, 'getTargetElement') && $tplCond->getTargetElement() && isset($elMap[$tplCond->getTargetElement()]) && method_exists($cond, 'setTargetElement')) {
                    $cond->setTargetElement($elMap[$tplCond->getTargetElement()]);
                }
                if (method_exists($tplCond, 'getNextElement') && $tplCond->getNextElement() && isset($elMap[$tplCond->getNextElement()]) && method_exists($cond, 'setNextElement')) {
                    $cond->setNextElement($elMap[$tplCond->getNextElement()]);
                }

                // Remap field reference via fieldMap
                if (method_exists($tplCond, 'getSourceField') && $tplCond->getSourceField() && isset($fieldMap[$tplCond->getSourceField()]) && method_exists($cond, 'setSourceField')) {
                    $cond->setSourceField($fieldMap[$tplCond->getSourceField()]);
                }

                $em->persist($cond);
            }
        }

        // 5) Flush once at the end
        $em->flush();

        return $this->redirectToRoute('app_capture_edit', ['id' => $capture->getId()]);
    }

    /** Copy a scalar/relation if matching getters/setters exist (Same Model). */
    private function copyIf(object $src, object $dst, string $name): void
    {
        $get = 'get' . $name;
        $set = 'set' . $name;
        if (method_exists($src, $get) && method_exists($dst, $set)) {
            $dst->{$set}($src->{$get}());
        }
    }

    /** Clone and set an object property if both accessors exist. */
    private function cloneObjectIf(object $src, object $dst, string $name, EntityManagerInterface $em, ?callable $after = null): void
    {
        $get = 'get' . $name;
        $set = 'set' . $name;

        if (!method_exists($src, $get) || !method_exists($dst, $set)) {
            return;
        }

        $value = $src->{$get}();
        if (!$value) {
            return;
        }

        $clone = clone $value;
        if ($after) {
            $after($clone);
        }

        $dst->{$set}($clone);
        $em->persist($clone);
    }


}
