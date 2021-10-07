<?php

namespace App\Controller\Admin;

use App\Entity\Associations;
use App\Repository\AssociationsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AssociationController extends AbstractController
{

    /**
     * @Route("/administration/associations", name="admin_association")
     */
    public function list(
        Request $request,
        AssociationsRepository $repository
    ) {

        $page = $request->query->getInt('page', 1);
        if ($page <= 0) {
            $page = 1;
            $paginator = $repository->findAllAssociations($page);
        } else {
            $paginator = $repository->findAllAssociations($page);
            $paginator = empty($paginator['items']) ? $repository->findAllAssociations(1) : $paginator;
        }

        return $this->render('admin/association/list.html.twig', [
            'paginator' => $paginator,
        ]);
    }

    /**
     * @Route("administration/associations/anonymisation/{slug}", name="admin_anonymize_association")
     */
    public function anonymize(
        Associations $association,
        EntityManagerInterface $em,
        Request $request
    ): Response {

        // nombre de cas :
        // deja deleted a verif
        // affichage du link seulement si deleted = 0
        // request pour ALL data
        // creer la route pour real delete et faire grossomodo la meme qu'ici


        $data = json_decode($request->getContent(), true);
        if ($this->isCsrfTokenValid('anonymize' . $association->getSlug(), $data['_token'])) {

            $association->setIsDeleted(1);
            $association->setName('deleted' . $association->getId());
            $association->setEmail('deleted' . $association->getId() . '@deleted.del');
            $association->setAddress('deleted');
            $association->setZip('00000');
            $association->setCity('deleted');
            $association->setFixNumber(0000000000);
            $association->setCellNumber(0000000000);
            $association->setFaxNumber(0000000000);
            $association->setDescription('deleted');
            $association->setWebSite('deleted');
            $association->setFacebook('deleted');
            $association->setLinkedin('deleted');
            $association->setYoutube('deleted');
            $association->setTwitter('deleted');
    //        // TODO : When association delete user must loose is role
            $em->flush();

            return $this->json(['code' => 200, 'message'=> 'Ok'], 200 );
        }
        // do a better 403 ?
        return $this->json(['code' => 403, 'message'=> 'Pas ok'], 403 );
    }
}
