<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Livre;
use App\Entity\Exemplaire;

use Symfony\Component\HttpFoundation\Response;

class ExemplesModeleController extends AbstractController
{


    /**
     * @Route ("/exemples/modele/exemple/find/one/by")
     */
    public function exempleFindOneBy()
    {
        // obtenir le entity manager
        $em = $this->getDoctrine()->getManager();
        // obtenir le repository
        $rep = $em->getRepository(Livre::class);

        // on obtient l'objet, le filtre est envoyé sous la forme d'un array
        $livre = $rep->findOneBy(array('titre' => 'Life and Fate'));

        // on stocke le résultat dans un array associatif 
        // pour l'envoyer à la vue comme d'habitude
        $vars = ['unLivre' => $livre];

        // on renvoie l'objet à la vue, rien ne change ici
        return $this->render("exemples_modele/exemple_find_one_by.html.twig", $vars);
    }




    // SELECT: find (chercher par id)

    /**
     * @Route ("exemples/modele/exemple/find")
     */
    public function exempleFind()
    {
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Livre::class);

        $livre = $rep->find(1);
        $vars = ['unLivre' => $livre];
        return $this->render("exemples_modele/exemple_find.html.twig", $vars);
    }


    // SELECT: findBy (chercher par un ou plusieurs champs, filtre array)

    /**
     * @Route ("exemples/modele/exemple/find/by")
     */
    public function exempleFindBy()
    {
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Livre::class);

        // notez que findBy renverra toujours un array même s'il trouve 
        // qu'un objet
        $livres = $rep->findBy(array(
            'prix' => '18',
            'datePublication' => new \DateTime('1960-1-1')
        ));
        $vars = ['livres' => $livres];
        return $this->render("exemples_modele/exemple_find_by.html.twig", $vars);
    }


    // SELECT: findAll (chercher par un ou plusieurs champs, filtre array)

    /**
     * @Route ("exemples/modele/exemple/find/all")
     */
    public function exempleFindAll()
    {
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Livre::class);

        // notez que findBy renverra toujours un array même s'il trouve 
        // qu'un objet
        $livres = $rep->findAll();
        $vars = ['livres' => $livres];

        dump(count($livres[0]->getExemplaires()));
        dump($livres[0]->getExemplaires()[1]->getEtat());
        die();


        return $this->render("exemples_modele/exemple_find_all.html.twig", $vars);
    }



    // INSERT
    /**
     * @Route ("exemples/modele/exemple/insert")
     */
    public function exempleInsert()
    {
        $em = $this->getDoctrine()->getManager();
        // créer l'objet
        $livre = new Livre();
        $livre->setTitre("Guerre et paix");
        $livre->setPrix(20);
        $livre->setDescription(" l’histoire de la Russie à l’époque de Napoléon Ier, notamment la campagne de Russie en 1812. Léon Tolstoï y développe une théorie fataliste de l’histoire, où le libre arbitre n’a qu’une importance mineure et où tous les événements n’obéissent qu’à un déterminisme historique inéluctable. ");

        // lier l'objet avec la BD
        $em->persist($livre);
        // écrire l'objet dans la BD
        $em->flush();

        return $this->render("exemples_modele/exemple_insert.html.twig");
    }

    // UPDATE
    /**
     * @Route ("exemples/modele/exemple/update")
     */
    public function exempleUpdate()
    {
        $em = $this->getDoctrine()->getManager();
        // on obtient d'abord un livre
        $unLivre = $em->getRepository(Livre::class)->findOneBy(array("titre" => "Vie et destin"));
        $unLivre->setTitre("Toto est content");
        // pas besoin de persist 
        // quand on obtient un objet de la BD
        // $em->persist ($unLivre); 
        $em->flush();
        return $this->render("exemples_modele/exemple_update.html.twig");
    }

    // DELETE
    /**
     * @Route ("exemples/modele/exemple/delete")
     */
    public function exempleDelete()
    {
        $em = $this->getDoctrine()->getManager();
        $unLivre = $em->getRepository(Livre::class)->findOneBy(array("titre" => "Toto est content"));
        // pas besoin de persist
        // quand on obtient un objet de la BD 
        // $em->persist ($unLivre); 
        $em->remove($unLivre);
        $em->flush();
        return $this->render("exemples_modele/exemple_delete.html.twig");
    }


    // Detach

    /**
     * @Route ("/exemples/modele/exemple/detach")
     */
    public function exempleDetach()
    {
        $em = $this->getDoctrine()->getManager();
        $livre = $em->getRepository(Livre::class)->findOneBy(array("titre" => "Life and Fate"));
        $livre->setTitre("Totorito");
        $em->detach($livre);
        // ce flush ne fera rien, l'éntité a été détachée de l'unité du travail
        $em->flush();
        dump($livre);
        die();
        return $this->render("exemplesModele/exemple_detach.html.twig");
    }


    // Merge

    /**
     * @Route ("/exemples/modele/exemple/merge")
     */
    public function exempleMergeEntite()
    {
        $em = $this->getDoctrine()->getManager();
        $livre = new Livre();
        $livre->setTitre("Pizza pizza");
        // on crée une copie de l'entité. Cette copie sera gérée 
        // dans l'unité mais pas le livre original
        $nouveauLivre = $em->merge($livre);
        $livre->setTitre("Tururu"); // ne changera pas dans la BD
        //quand on fera flush plus bas

        // ce livre changera, cette copie est déjà dans l'unité de travail.
        // observez qu'il n'y aura pas un "persist"
        $nouveauLivre->setTitre("Nonono");
        $nouveauLivre->setPrix(40);
        $nouveauLivre->setDescription("Super");
        $nouveauLivre->setDatePublication(new \DateTime);
        // ce flush mettra à jour la BD pour "nouveauLivre"
        $em->flush();
        return $this->render("exemples_modele/exemple_merge.html.twig");
    }

    // Refresh

    /**
     * @Route ("/exemples/modele/exemple/refresh")
     */
    public function exempleRefresh()
    {
        $em = $this->getDoctrine()->getManager();
        $unLivre = $em->getRepository(Livre::class)->findOneBy(array("titre" => "Life and fate"));
        $unLivre->setTitre("La vie est belle");
        // dd ($unLivre);

        // recharge le livre de la BD, il y aura le titre original
        $em->refresh($unLivre);

        // decommentez ces deux lignes pour vérifier que l'objet a le titre original
        // dump ($unLivre);
        // die();

        $em->persist($unLivre);
        // rien ne change dans la BD
        $em->flush();
        return $this->render("exemples_modele/exemple_refresh.html.twig");
    }


    /**
     * @Route ("/exemples/modele/exemple/update/hydrate");
     */
    public function exempleUpdateHydrate()
    {
        $em = $this->getDoctrine()->getManager();

        $rep = $em->getRepository(Livre::class);
        $livre = $rep->findOneBy(['titre' => 'Nonono']);

        // on ne fera pas plein de sets
        // $livre->setPrix (60);
        // $livre->setTitre ("The Shining");
        // $livre->setTitre ("43524352435");
        $livre->hydrate([
            'titre' => 'The Shining SE',
            'ISBN' => '5050505050'
        ]);

        $em->flush();

        return new Response("objet modifié");
    }


    // INSERT avec hydrate
    /**
     * @Route ("/exemples/modele/exemple/insert/hydrate");
     */
    public function exempleInsertHydrate()
    {
        $livre = new Livre([
            'titre' => 'Les misérables 2',
            'prix' => 80,
            'description' => 'nananana',
            'ISBN' => '33243234234',
            'datePublication' => new DateTime("2000/2/20")
        ]);
        $em = $this->getDoctrine()->getManager();
        $em->persist($livre);
        $em->flush();

        return new Response("insert ok hydrate");
    }
}
