<?php

class Panier {
    public static function getPanier() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        return isset($_SESSION['panier']) ? $_SESSION['panier'] : [];
    }

    public static function addToCart($id, $nom, $prix, $image, $quantite = 1) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        // Récupérer le stock du produit
        require_once __DIR__ . '/Creation.php';
        $creation = Creation::getCreationById($id);
        $stock = $creation ? $creation->getStock() : 0;

        // Ne rien faire si le produit est en rupture de stock
        if ($stock <= 0) {
            return;
        }

        if (!isset($_SESSION['panier'])) $_SESSION['panier'] = [];
        
        foreach ($_SESSION['panier'] as &$item) {
            if ($item['id'] == $id) {
                // S'assurer de ne pas dépasser le stock
                $item['quantite'] = min($stock, $item['quantite'] + $quantite);
                return;
            }
        }
        
        $_SESSION['panier'][] = [
            'id' => $id,
            'nom' => $nom,
            'prix' => $prix,
            'image' => $image,
            'quantite' => min($stock, $quantite),
            'stock' => $stock
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

        // Si la quantité est de 0 ou moins, on supprime l'article
        if ($quantite <= 0) {
            self::removeFromCart($id);
            return;
        }

        // Récupérer le stock pour vérifier
        require_once __DIR__ . '/Creation.php';
        $creation = Creation::getCreationById($id);
        $stock = $creation ? $creation->getStock() : 0;

        foreach ($_SESSION['panier'] as &$item) {
            if ($item['id'] == $id) {
                // Mettre à jour la quantité sans dépasser le stock
                $item['quantite'] = min($stock, $quantite);
                break;
            }
        }
    }
} 