<?php

class Panier {
    public static function getPanier() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        return isset($_SESSION['panier']) ? $_SESSION['panier'] : [];
    }

    public static function addToCart($id, $nom, $prix, $image, $quantite = 1) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['panier'])) $_SESSION['panier'] = [];
        foreach ($_SESSION['panier'] as &$item) {
            if ($item['id'] == $id) {
                $item['quantite'] += $quantite;
                return;
            }
        }
        $_SESSION['panier'][] = [
            'id' => $id,
            'nom' => $nom,
            'prix' => $prix,
            'image' => $image,
            'quantite' => $quantite
        ];
    }

    public static function removeFromCart($id) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['panier'])) return;
        $_SESSION['panier'] = array_filter($_SESSION['panier'], function($item) use ($id) {
            return $item['id'] != $id;
        });
    }

    public static function updateQuantity($id, $quantite) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['panier'])) return;
        foreach ($_SESSION['panier'] as &$item) {
            if ($item['id'] == $id) {
                $item['quantite'] = $quantite;
                break;
            }
        }
    }
} 