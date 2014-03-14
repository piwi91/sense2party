<?php

namespace Piwi\S2p\PhotoBundle\Controller;

use Gaufrette\Exception\FileNotFound;
use Piwi\S2p\PhotoBundle\Entity\Photo;
use Piwi\S2p\PhotoBundle\Form\AlbumFormType;
use Piwi\S2p\PhotoBundle\Entity\Album;
use Piwi\S2p\PhotoBundle\Form\EditAlbumFormType;
use Piwi\S2p\PhotoBundle\Form\ExistingAlbumFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class PhotoController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        if (!$this->get('security.context')->isGranted('ROLE_MEMBER')) {
            $albums = $em->getRepository('PiwiS2pPhotoBundle:Album')->findBy(array('public' => true), array('date' => 'DESC'));
        } else {
            $albums = $em->getRepository('PiwiS2pPhotoBundle:Album')->findBy(array(), array('date' => 'DESC'));
        }

        return $this->render('PiwiS2pPhotoBundle:Photo:index.html.twig', array(
            'albums' => $albums
        ));
    }

    public function albumAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $album = $em->getRepository('PiwiS2pPhotoBundle:Album')->findOneBySlug($slug);
        if (!$album) {
            throw $this->createNotFoundException('piwi.s2p.photo.photo.album.exception');
        }

        if (!$album->getPublic() && !$this->get('security.context')->isGranted('ROLE_MEMBER')) {
            throw new AccessDeniedException('No access');
        }

        // Count views
        $album->setViews($album->getViews() + 1);
        $em->persist($album);
        $em->flush();

        return $this->render('PiwiS2pPhotoBundle:Photo:album.html.twig', array(
            'album' => $album
        ));
    }

    public function editAction(Request $request, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        $album = $em->getRepository('PiwiS2pPhotoBundle:Album')->findOneBySlug($slug);
        if (!$album) {
            throw $this->createNotFoundException('piwi.s2p.photo.photo.edit.exception');
        }

        if ($album->getUser() !== $this->getUser() && !$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('No access');
        }

        $form = $this->createForm(new EditAlbumFormType(), $album, array(
            'show_legend' => false
        ));
        if ($request->isMethod('POST')) {
            $form->submit($request);
            if ($form->isValid()) {
                $em->persist($album);
                $em->flush();

                $this->get('session')->getFlashBag()->add(
                    'success', $this->get('translator')->trans(
                        'piwi.s2p.photo.photo.edit.flashbag.success', array('%album%' => $album->getTitle())
                    )
                );

                return $this->redirect(
                    $this->generateUrl('piwi_s2p_photo_photo_index')
                );
            }
        }

        return $this->render('PiwiS2pPhotoBundle:Photo:edit.html.twig',
            array(
                'form' => $form->createView(),
                'album' => $album
            )
        );
    }

    public function step1Action()
    {
        $formAlbums = $this->createForm(new ExistingAlbumFormType(), null, array(
            'show_legend' => false
        ));

        $album = new Album();
        $form = $this->createForm(new AlbumFormType(), $album, array(
            'show_legend' => false
        ));

        return $this->render('PiwiS2pPhotoBundle:Photo:add/step1.html.twig',
            array(
                'form' => $form->createView(),
                'form_albums' => $formAlbums->createView()
            )
        );
    }

    public function step2Action(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $album = new Album();
        $form = $this->createForm(new AlbumFormType(), $album, array(
            'show_legend' => false
        ));

        if ($request->isMethod('POST')) {
            $valid = false;
            if ($albumForm = $request->get('piwi_s2p_photo_photo_choose_album')) {
                if (array_key_exists('album', $albumForm)) {
                    $album = $em->getRepository('PiwiS2pPhotoBundle:Album')->find(
                        $albumForm['album']
                    );
                    $valid = true;
                }
            } else {
                $form->submit($request);
                if ($form->isValid()) {
                    if ($user = $this->getUser()) {
                        $album->setUser($user);
                    }

                    $em->persist($album);
                    $em->flush();

                    $valid = true;
                }
            }
            if ($valid === true) {
                $this->get('session')->getFlashBag()->add(
                    'success', 'piwi.s2p.photo.photo.add.step1.flashbag.success'
                );

                return $this->render('PiwiS2pPhotoBundle:Photo/add:step2.html.twig',
                    array (
                        'album' => $album
                    )
                );
            }
        }

        $this->get('session')->getFlashBag()->add(
            'warning', 'piwi.s2p.photo.photo.add.step1.flashbag.warning'
        );

        return $this->redirect(
            $this->generateUrl('piwi_s2p_photo_photo_add')
        );
    }

    public function step3Action(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        if ($request->isMethod('POST')) {
            $albumId = $request->get('album_id');

            $album = $em->getRepository('PiwiS2pPhotoBundle:Album')->find($albumId);
            if (!$album) {
                throw $this->createNotFoundException('piwi.s2p.photo.photo.add.step3.exception');
            }

            $manager = $this->get('oneup_uploader.orphanage_manager')->get('gallery');

            $files = $manager->uploadFiles();

            /** @var $file \Gaufrette\File */
            foreach ($files as $file) {
                $photo = new Photo();
                $photo->setAlbum($album);
                if ($user = $this->getUser()) {
                    $photo->setUser($user);
                }
                $photo->setTitle($file->getName());
                $photo->setFilename($file->getName());

                if (!$album->getPreview()) {
                    $album->setPreview($photo);
                    $em->persist($album);
                }

                $em->persist($photo);
            }

            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'success', 'piwi.s2p.photo.photo.add.step3.flashbag.success'
            );

            return $this->redirect(
                $this->generateUrl('piwi_s2p_photo_photo_album', array('slug' => $album->getSlug()))
            );
        }
    }

    public function deleteAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $album = $em->getRepository('PiwiS2pPhotoBundle:Album')->findOneBySlug($slug);
        if (!$album) {
            throw $this->createNotFoundException('piwi.s2p.photo.photo.edit.exception');
        }

        if ($album->getUser() !== $this->getUser() && !$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('No access');
        }

        // First remove the preview picture
        $album->setPreview(null);
        $em->persist($album);

        // Iterate throw all photos and delete the file and entity
        /** @var $photo \Piwi\S2p\PhotoBundle\Entity\Photo */
        foreach ($album->getPhotos() as $photo) {
            $gaufrette = $this->get('gaufrette.photos_filesystem');
            try {
                $gaufrette->delete($photo->getFilename());
            } catch (FileNotFound $e) {
                // Do nothing
            }
            $em->remove($photo);
        }
        $em->flush();

        // At the end, remove the whole album
        $em->remove($album);
        $em->flush();

        $this->get('session')->getFlashBag()->add(
            'success', $this->get('translator')->trans(
                'piwi.s2p.photo.photo.delete.flashbag.success', array('%album%' => $album->getTitle())
            )
        );

        return $this->redirect(
            $this->generateUrl('piwi_s2p_photo_photo_index')
        );
    }

    public function deletePhotoAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $photo = $em->getRepository('PiwiS2pPhotoBundle:Photo')->find($id);
        if (!$photo) {
            throw $this->createNotFoundException('piwi.s2p.photo.photo.edit.exception');
        }

        if ($photo->getUser() !== $this->getUser() && !$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('No access');
        }

        $album = $photo->getAlbum();
        if ($album->getPreview() == $photo->getFilename()) {
            $album->setPreview(null);
        }
        $album->setCount($album->getCount() - 1);
        $em->persist($album);
        $em->flush();

        $gaufrette = $this->get('gaufrette.photos_filesystem');
        try {
            $gaufrette->delete($photo->getFilename());
        } catch (FileNotFound $e) {
            // Do nothing
        }
        $em->remove($photo);
        $em->flush();

        $this->get('session')->getFlashBag()->add(
            'success', $this->get('translator')->trans(
                'piwi.s2p.photo.photo.deletephoto.flashbag.success', array('%photo%' => $photo->getTitle())
            )
        );

        return $this->redirect(
            $this->generateUrl('piwi_s2p_photo_photo_album', array('slug' => $photo->getAlbum()->getSlug()))
        );
    }

    public function selectPreviewAction($slug, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $photo = $em->getRepository('PiwiS2pPhotoBundle:Photo')->find($id);
        if (!$photo) {
            throw $this->createNotFoundException('piwi.s2p.photo.photo.edit.exception');
        }

        $album = $em->getRepository('PiwiS2pPhotoBundle:Album')->findOneBySlug($slug);
        if (!$album) {
            throw $this->createNotFoundException('piwi.s2p.photo.photo.edit.exception');
        }

        if ($album->getUser() !== $this->getUser() && !$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('No access');
        }

        $album->setPreview($photo);
        $em->persist($album);
        $em->flush();

        $this->get('session')->getFlashBag()->add(
            'success', $this->get('translator')->trans(
                'piwi.s2p.photo.photo.selectpreview.flashbag.success', array(
                    '%photo%' => $photo->getTitle(),
                    '%album%' => $album->getTitle()
                )
            )
        );

        return $this->redirect(
            $this->generateUrl('piwi_s2p_photo_photo_album', array('slug' => $album->getSlug()))
        );
    }
}
