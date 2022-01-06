<?php

namespace App\Service\Panier;


use App\Repository\VehicleRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PanierService
{

    public $session;
    public $vehicleRepository;

    public function __construct(SessionInterface $session, VehicleRepository $vehicleRepository)
    {
        $this->session = $session;
        $this->vehicleRepository = $vehicleRepository;

    }

    public function add(int $id)
    {
        $panier = $this->session->get('panier', []);

        if (empty($panier[$id])):
            $panier[$id] = 1;

        endif;

        $this->session->set('panier', $panier);
    }

    public function remove(int $id)
    {
        $panier = $this->session->get('panier', []);

        if (!empty($panier[$id]) && $panier[$id] !== 1):
            $panier[$id]--;
        else:
            unset($panier[$id]);
        endif;

        $this->session->set('panier', $panier);
    }

    public function delete(int $id)
    {
        $panier = $this->session->get('panier', []);

        if (!empty($panier[$id])):
            unset($panier[$id]);
        endif;

        $this->session->set('panier', $panier);
    }


    public function getFullCart(): array
    {
        $panier = $this->session->get('panier', []);

        $panierDetail = [];
        foreach ($panier as $id):

            $panierDetail[]=[
                'vehicle'=>$this->vehicleRepository->find($id),
                
            ];

        endforeach;

        return $panierDetail;


    }


}