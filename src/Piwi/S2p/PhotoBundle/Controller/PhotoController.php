<?php

namespace Piwi\S2p\PhotoBundle\Controller;

use Piwi\S2p\PhotoBundle\Entity\Photo;
use Piwi\S2p\PhotoBundle\Form\AlbumFormType;
use Piwi\S2p\PhotoBundle\Entity\Album;
use Piwi\S2p\PhotoBundle\Form\ExistingAlbumFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PhotoController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $albums = $em->getRepository('PiwiS2pPhotoBundle:Album')->findBy(array(), array('date' => 'DESC'));

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

        return $this->render('PiwiS2pPhotoBundle:Photo:album.html.twig', array(
            'album' => $album
        ));
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
                $album = $em->getRepository('PiwiS2pPhotoBundle:Album')->find(
                    $albumForm['album']
                );
                $valid = true;
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
}
