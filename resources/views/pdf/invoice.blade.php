<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Facture #{{ $order->id }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 14px;
            background: #f9f9f9;
            color: #333;
        }
        .container {
            background: #fff;
            padding: 32px 32px 24px 32px;
            border-radius: 10px;
            box-shadow: 0 0 8px rgba(0,0,0,0.06);
            max-width: 800px;
            margin: auto;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #f0f0f0;
            margin-bottom: 24px;
            padding-bottom: 16px;
        }
        .logo {
            height: 48px;
        }
        .company-info {
            text-align: right;
        }
        h1 {
            color: #1869ff;
            font-size: 2rem;
            margin-bottom: 0;
        }
        .details {
            margin-bottom: 24px;
            padding: 16px;
            background: #f6faff;
            border-radius: 7px;
        }
        .details strong {
            color: #1869ff;
        }
        .section-title {
            color: #1869ff;
            font-size: 1.1rem;
            margin-top: 32px;
            margin-bottom: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background: #1869ff;
            color: #fff;
            font-weight: 600;
            border: none;
            padding: 12px 8px;
            font-size: 15px;
        }
        td {
            border-bottom: 1px solid #eaeaea;
            padding: 10px 8px;
        }
        tr:last-child td {
            border-bottom: none;
        }
        .total-row td {
            font-weight: bold;
            font-size: 16px;
            color: #1869ff;
            background: #f6faff;
        }
        .footer {
            margin-top: 32px;
            text-align: center;
            font-size: 13px;
            color: #888;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <div>
            <img src="{{ public_path('logo.png') }}" class="logo" alt="Logo">
            <div style="margin-top: 6px; font-size: 13px; color: #888;">
                www.shopvib.sn
            </div>
        </div>
        <div class="company-info">
            <strong>Ma Boutique SHOPVIB</strong><br>
            123 Boulevard Canal IV<br>
            11500 Dakar, Senegal<br>
            NINEA : 123 456 789 00012
        </div>
    </div>

    <h1>Numero de Facture : {{ $order->id }}</h1>
    <div class="details">
        <div><strong>Date de commande :</strong> {{ $order->ordered_at ?? $order->created_at->format('d/m/Y') }}</div>
        <div><strong>Mode de paiement :</strong> {{ $order->payment_method }}</div>
        <div><strong>Client :</strong> {{ $order->user->name ?? 'Client inconnu' }} ({{ $order->user->email ?? '' }})</div>
        @if($order->shippingAddress)
            <div>
                <strong>Adresse de livraison :</strong>
                {{ $order->shippingAddress->name }},
                {{ $order->shippingAddress->address_line1 }}
                @if($order->shippingAddress->address_line2), {{ $order->shippingAddress->address_line2 }} @endif
                , {{ $order->shippingAddress->city }} {{ $order->shippingAddress->postal_code }}
            </div>
        @endif
    </div>

    <div class="section-title">Détails des produits achetés</div>
    <table>
        <thead>
        <tr>
            <th>Produit</th>
            <th>Quantité</th>
            <th>Prix Unitaire (FCFA)</th>
            <th>Sous-total (FCFA)</th>
        </tr>
        </thead>
        <tbody>
        @foreach($order->orderItems as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->price, 0) }}FCFA</td>
                <td>{{ number_format($item->total, 0) }}FCFA</td>
            </tr>
        @endforeach
        <tr class="total-row">
            <td colspan="3" style="text-align:right;">Total TTC :</td>
            <td>{{ number_format($order->total, 0) }} FCFA</td>
        </tr>
        </tbody>
    </table>

    <div class="footer">
        Merci de nous faire confiance ! Votre satisfaction est notre priorité.<br>
    </div>

    <div class="footer">
        Facture générée automatiquement. Merci pour votre commande !<br>
        Pour toute question, contactez-nous à support@shopvib.sn
    </div>
</div>
</body>
</html>
