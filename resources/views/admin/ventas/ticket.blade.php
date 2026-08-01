<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket #{{ $orden->id }} - Intensa Jeans</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Courier New', monospace;
            font-size: 13px;
            color: #111;
            display: flex;
            justify-content: center;
            padding: 20px;
            background: #f5f5f5;
        }
        .ticket {
            width: 320px;
            background: #fff;
            padding: 20px 16px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .centro { text-align: center; }
        .nombre-tienda {
            font-size: 22px;
            font-weight: bold;
            letter-spacing: 2px;
        }
        .linea { border-top: 1px dashed #333; margin: 12px 0; }
        .linea-delgada { border-top: 1px dotted #999; margin: 8px 0; }
        .estado-badge {
            display: inline-block;
            padding: 4px 16px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
            margin: 8px 0;
            background: #d4edda;
            color: #155724;
        }
        table.items {
            width: 100%;
            border-collapse: collapse;
        }
        table.items th {
            text-align: left;
            border-bottom: 1px solid #333;
            padding: 4px 0;
        }
        table.items td {
            padding: 4px 0;
            vertical-align: top;
        }
        table.items .cant { text-align: center; width: 30px; }
        table.items .precio { text-align: right; white-space: nowrap; }
        table.items .subtotal { text-align: right; white-space: nowrap; }
        table.items .desc { padding-left: 4px; }
        .total-row td {
            border-top: 1px solid #333;
            font-weight: bold;
            padding-top: 6px;
            font-size: 15px;
        }
        .footer { margin-top: 12px; font-size: 11px; color: #666; text-align: center; }
        .info-line { display: flex; justify-content: space-between; padding: 2px 0; }
        @media print {
            body { background: #fff; padding: 0; }
            .ticket { box-shadow: none; border-radius: 0; width: 100%; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="centro">
            <div class="nombre-tienda">INTENSA JEANS</div>
            <div style="font-size: 11px; color: #555;">Tienda de Indumentaria</div>
            <div class="linea"></div>
        </div>

        <div class="info-line">
            <span>N° Pedido:</span>
            <span>#{{ $orden->id }}</span>
        </div>
        <div class="info-line">
            <span>Fecha:</span>
            <span>{{ $orden->created_at->format('d/m/Y H:i') }}</span>
        </div>
        <div class="info-line">
            <span>Cliente:</span>
            <span>{{ $orden->nombre_contacto ?? optional($orden->user)->name ?? 'N/A' }}</span>
        </div>
        @if ($orden->telefono_contacto)
            <div class="info-line">
                <span>Teléfono:</span>
                <span>{{ $orden->telefono_contacto }}</span>
            </div>
        @endif
        <div class="info-line">
            <span>Método de pago:</span>
            <span>
                @php
                    $mapa = ['efectivo'=>'Efectivo','tarjeta'=>'Tarjeta','transferencia'=>'Transferencia','tarjeta_debito'=>'Tarjeta Débito','tarjeta_credito'=>'Tarjeta Crédito'];
                @endphp
                {{ $mapa[$orden->metodo_pago] ?? $orden->metodo_pago ?? '—' }}
            </span>
        </div>

        <div class="centro">
            <span class="estado-badge">PAGADA</span>
        </div>

        <div class="linea"></div>

        @php
            $totalItems = 0;
            $totalDescuento = 0;
        @endphp

        <table class="items">
            <thead>
                <tr>
                    <th class="cant">Cant</th>
                    <th class="desc">Producto</th>
                    <th class="precio">Precio</th>
                    <th class="subtotal">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orden->items as $item)
                    @php
                        $totalItems += $item->subtotal;
                        $precioConDescuento = $item->precio_unitario;
                        $producto = $item->producto;
                        $aplicaDescEf = in_array($orden->metodo_pago, ['efectivo', 'transferencia']) && $producto;
                        $porcDesc = $aplicaDescEf ? (int) $producto->porc_desc_ef : 0;
                        $precioEf = $porcDesc > 0 ? $item->precio_unitario * (1 - $porcDesc / 100) : $item->precio_unitario;
                        $descItem = ($item->precio_unitario - $precioEf) * $item->cantidad;
                        $totalDescuento += $descItem;
                    @endphp
                    <tr>
                        <td class="cant">{{ $item->cantidad }}</td>
                        <td class="desc">
                            {{ $item->producto->nombre ?? 'Producto' }}
                            <br><small style="color:#888;">SKU: {{ $item->producto->sku ?? 'S/N' }}</small>
                            @if ($item->talle)
                                <br><small style="color:#888;">Talle: {{ $item->talle }}</small>
                            @endif
                            @if ($descItem > 0)
                                <br><small style="color:#dc3545;">-${{ number_format($item->precio_unitario - $precioEf, 0, ',', '.') }} c/u</small>
                            @endif
                        </td>
                        <td class="precio">${{ number_format($item->precio_unitario, 0, ',', '.') }}</td>
                        <td class="subtotal">${{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="3" style="text-align: right;">TOTAL</td>
                    <td class="subtotal">${{ number_format($totalItems, 0, ',', '.') }}</td>
                </tr>
                @if ($totalDescuento > 0)
                    <tr>
                        <td colspan="3" style="text-align: right; color: #28a745;">Descuento</td>
                        <td class="subtotal" style="color: #28a745;">-${{ number_format($totalDescuento, 0, ',', '.') }}</td>
                    </tr>
                    <tr style="font-weight: bold; font-size: 16px;">
                        <td colspan="3" style="text-align: right;">TOTAL PAGADO</td>
                        <td class="subtotal">${{ number_format($totalItems - $totalDescuento, 0, ',', '.') }}</td>
                    </tr>
                @endif
            </tfoot>
        </table>

        <div class="linea"></div>

        <div class="footer">
            <p>Gracias por tu compra 💙</p>
            <p style="margin-top: 4px;">Intensa Jeans - @intensa.ok</p>
        </div>

        <div class="centro no-print" style="margin-top: 16px;">
            <button onclick="window.print()"
                style="padding: 10px 32px; border: none; border-radius: 6px; background: #1a3352; color: #fff; font-weight: bold; cursor: pointer;">
                Imprimir
            </button>
            <br><br>
            <a href="{{ route('admin.ventas.index') }}" style="color: #1a3352; font-size: 12px;">← Volver a ventas</a>
        </div>
    </div>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</body>
</html>