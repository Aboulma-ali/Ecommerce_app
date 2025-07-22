<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de paiement</title>
    <style>
        /* Styles simples pour une meilleure compatibilité */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        h1 {
            color: #000;
        }
        .payment-panel {
            background-color: #f2f2f2;
            padding: 15px;
            margin: 20px 0;
            border-left: 5px solid #28a745; /* Vert pour la confirmation de paiement */
        }
        .button {
            display: inline-block;
            background-color: #007bff;
            color: #ffffff !important; /* !important pour forcer la couleur sur certains clients mail */
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Confirmation de votre paiement</h1>

    <p>Bonjour {{ $order->user->name }},</p>

    <p>Nous avons bien reçu la confirmation de paiement pour votre commande <strong>#{{ $order->id }}</strong>.</p>

    <div class="payment-panel">
        Total payé : <strong>{{ number_format($order->total, 2, ',', ' ') }} €</strong><br>
        Mode de paiement : {{-- Votre logique ternaire est parfaite et est conservée --}}
        {{ $order->payment_method === 'en_ligne' ? 'Paiement en ligne' : 'Paiement à la livraison' }}
        Statut du paiement :
        {{ $order->payment_status === 'payé' ? 'Payé' : 'Non payé' }}
    </div>

    <p>Vous pouvez consulter les détails de votre commande et télécharger votre facture à tout moment depuis votre espace client.</p>

    <p>
        Merci de votre confiance,<br>
        L'équipe de {{ config('app.name') }}
    </p>
</div>
</body>
</html>
