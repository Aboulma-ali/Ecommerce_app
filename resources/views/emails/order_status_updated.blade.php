<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Mise à jour de commande</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
<div style="max-width: 600px; margin: 0 auto; padding: 20px;">
    <h1 style="color: #2c3e50;">Mise à jour de votre commande #{{ $order->id }}</h1>

    <p>Bonjour {{ $order->user->name }},</p>

    <p>Bonne nouvelle ! Le statut de votre commande a été mis à jour.</p>

    <div style="background-color: #e8f5e8; padding: 15px; border-radius: 5px; margin: 20px 0;">
        <p style="font-size: 18px; font-weight: bold; margin: 0; color: #2d5a2d;">
            Nouveau statut : {{ ucfirst($order->status) }}
        </p>
    </div>

    @if($order->status === 'expédiée')
        <p>Votre colis a été remis au transporteur et est en cours d'acheminement. Vous pouvez suivre son avancée prochainement.</p>
    @elseif($order->status === 'livrée')
        <p>Votre commande a été livrée ! Nous espérons que vos produits vous plaisent.</p>
    @elseif($order->status === 'annulée')
        <p>Votre commande a été annulée conformément à votre demande ou en raison d'un problème. N'hésitez pas à nous contacter pour plus d'informations.</p>
    @endif

    <p>Merci pour votre confiance,<br>
        L'équipe de {{ config('app.name') }}</p>
</div>
</body>
</html>
