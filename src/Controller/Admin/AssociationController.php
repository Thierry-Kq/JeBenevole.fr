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
            $paginator = $repository->findAllAssociationsAdmin($page);
        } else {
            $paginator = $repository->findAllAssociationsAdmin($page);
            $paginator = empty($paginator['items']) ? $repository->findAllAssociationsAdmin(1) : $paginator;
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

        if ($association->getIsDeleted()) {
            return $this->json(['code' => 'warning', 'message'=> 'C\'ette association à déjà été anonymisée'], 200 );
        }

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

            return $this->json(['code' => 'success', 'message'=> 'L\'association à bien été anonymisée'], 200 );
        }

        return $this->json(['code' => 'error', 'message'=> 'Token invalide'], 200 );
    }

    /**
     * @Route("administration/associations/suppression/{slug}", name="admin_delete_association")
     */
    public function delete(
        Associations $association,
        EntityManagerInterface $em,
        Request $request
    ): Response {

        $data = json_decode($request->getContent(), true);
        if ($this->isCsrfTokenValid('delete' . $association->getSlug(), $data['_token'])) {
            $name = $association->getName();
            // todo : what todo if asso got Offers ?
            $em->remove($association);
            $em->flush();

            return $this->json(['code' => 'success', 'message'=> 'L\'association \'' . $name . '\' a bien été supprimée'], 200 );
        }

        return $this->json(['code' => 'error', 'message'=> 'Token invalide'], 200 );



    }
}
