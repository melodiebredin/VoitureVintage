<?php

namespace App\Service\Panier;

use App\Repository\VehicleRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PanierService
{

    public $session;
    public $movieRepository;

    public function __construct(SessionInterface $session, VehicleRepository $movieRepository)
    {
        $this->session = $session;
        $this->movieRepository = $movieRepository;

    }

    public function add(int $id)
    {
        // AJOUT AU PANIER
        // si il n'y a pas de panier de créé, crée en moi un vide
        $panier = $this->session->get('panier', []);

        if (empty($panier[$id])):
            $panier[$id] = 1;
        else:
            $panier[$id]++;
        endif;

// ($panier[$id] -> correspond au produit séléctionné pour l'ajout au panier, si il est deja dedans on ajoute 1 à la quantité, sinon on ajoute le produit complet

        $this->session->set('panier', $panier);
    }

    public function remove(int $id)
    {
        // ENLEVER EN QTE DU PANIER
        $panier = $this->session->get('panier', []);

        // Si qté sup à 1 on décrémente de 1, si qté = 1  alors on supprime la ligne (unset)
        if (!empty($panier[$id]) && $panier[$id] !== 1):
            $panier[$id]--;
        else:
            unset($panier[$id]);
        endif;

        $this->session->set('panier', $panier);
    }




    public function delete(int $id)
    {
        // SUPPRIMER LE PANIER
        $panier = $this->session->get('panier', []);

        if (!empty($panier[$id])):
            unset($panier[$id]);
        endif;

        $this->session->set('panier', $panier);
    }


    public function getFullCart(): array
    {
        // POUR L'ENVOI EN BDD, voir le panier complet
        $panier = $this->session->get('panier', []);

        $panierDetail = [];

        foreach ($panier as $id => $quantite):

            $panierDetail[]=[
                'vehicle'=>$this->movieRepository->find($id),
                'quantity'=>$quantite
            ];

        endforeach;
        return $panierDetail;


    }

public function getTotal()
{
    // aller chercher le montant total
    $panier = $this->getFullCart();

    //dd($panier);

    $total=0;
    foreach($panier as $item=>$value):
        //dd($item, $value);
   
        //dd($value ['vehicle']);

        $total+=$value['vehicle']->getPrice()*$value['quantity'];
   
    endforeach;
    //dd($total);
    return $total;
}

}
