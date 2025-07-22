<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Confirmation de commande</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
<div style="max-width: 600px; margin: 0 auto; padding: 20px;">
    <h1 style="color: #2c3e50;">Confirmation de votre commande</h1>

    <p>Bonjour {{ $order->user->name }},</p>

    <p>Merci pour votre achat ! Votre commande #{{ $order->id }} a bien été enregistrée.</p>

    <h3>Récapitulatif de votre commande :</h3>

    <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
        <thead>
        <tr style="background-color: #f8f9fa;">
            <th style="border: 1px solid #ddd; padding: 12px; text-align: left;">Produit</th>
            <th style="border: 1px solid #ddd; padding: 12px; text-align: center;">Quantité</th>
            <th style="border: 1px solid #ddd; padding: 12px; text-align: right;">Prix unitaire</th>
            <th style="border: 1px solid #ddd; padding: 12px; text-align: right;">Total</th>
        </tr>
        </thead>
        <tbody>
        @foreach($order->orderItems as $orderItem)
            <tr>
                <td style="border: 1px solid #ddd; padding: 12px;">{{ $orderItem->product->name }}</td>
                <td style="border: 1px solid #ddd; padding: 12px; text-align: center;">{{ $orderItem->quantity }}</td>
                <td style="border: 1px solid #ddd; padding: 12px; text-align: right;">{{ number_format($orderItem->price, 2) }} €</td>
                <td style="border: 1px solid #ddd; padding: 12px; text-align: right;">{{ number_format($orderItem->total, 2) }} €</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <p style="font-size: 18px; font-weight: bold; text-align: right;">
        Total de la commande : {{ number_format($order->total, 2) }} €
    </p>

    <p>Vous recevrez un autre email lorsque votre commande sera expédiée.</p>

    <p>Merci,<br>L'équipe de {{ config('app.name') }}</p>
</div>
</body>
</html>
