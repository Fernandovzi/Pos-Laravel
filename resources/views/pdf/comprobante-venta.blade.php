<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Ticket</title>

@php
$ticketWidthMm      = (float) config('printing.ticket_width_mm', 80);
$ticketPaddingMm    = (float) config('printing.ticket_padding_mm', 2);
$printableWidthMm   = (float) config('printing.ticket_printable_width_mm', 72);
$extraInnerPadding  = 2.5;
$safeWidth          = max(min($printableWidthMm, $ticketWidthMm) - (($ticketPaddingMm + $extraInnerPadding) * 2), 36);
@endphp

<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@700&family=DM+Mono:wght@700&display=swap');

/* ── RESET + TODO EN NEGRITAS ── */
*,
*::before,
*::after {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    font-weight: 700 !important;
}

@page {
    margin: 2mm 0;
    size: {{ $ticketWidthMm }}mm auto;
}

html, body {
    font-family: 'DM Sans', 'Helvetica Neue', Arial, sans-serif;
    background: #fff;
    color: #111;
    width: {{ $ticketWidthMm }}mm;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
    font-weight: 700 !important;
}

body {
    margin: 0 auto;
}

/* ── TICKET WRAPPER ── */
.ticket {
    width: {{ $safeWidth }}mm;
    margin: 3mm auto;
    padding: 0 {{ $ticketPaddingMm + $extraInnerPadding }}mm;
}

/* ── UTILITIES ── */
.center { text-align: center; }
.right  { text-align: right; }
.bold   { font-weight: 700 !important; }
.mono   { font-family: 'DM Mono', 'Courier New', monospace; font-weight: 700 !important; }
.muted  { color: #000000; }
.upper  { text-transform: uppercase; }

/* ── DIVIDERS ── */
.div-solid {
    border: none;
    border-top: 1px solid #111;
    margin: 10px 0;
}

.div-dash {
    border: none;
    border-top: 1px dashed #333333;
    margin: 8px 0;
}

/* ══════════════════════════════
   1. ENCABEZADO
══════════════════════════════ */
.header {
    text-align: center;
    padding-bottom: 2px;
}

.logo {
    max-width: 100px;
    height: auto;
    display: block;
    margin: 0 auto 6px;
}

.brand-name {
    font-size: 16px;
    font-weight: 700 !important;
    letter-spacing: -0.3px;
    margin-bottom: 1px;
}

.brand-slogan {
    font-size: 9px;
    font-weight: 700 !important;
    color: #000000;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    margin-bottom: 7px;
}

.brand-meta {
    font-size: 9.5px;
    font-weight: 700 !important;
    color: #000000;
    line-height: 1.7;
}

/* ══════════════════════════════
   2. INFORMACIÓN DE LA VENTA
══════════════════════════════ */
.folio-wrap {
    text-align: center;
    margin: 8px 0 6px;
}

.folio-badge {
    display: inline-block;
    border: 1.5px solid #111;
    padding: 2px 9px;
    font-size: 11px;
    font-weight: 700 !important;
    font-family: 'DM Mono', monospace;
    letter-spacing: 1px;
}

.info-grid {
    width: 100%;
    border-collapse: collapse;
}

.info-grid td {
    padding: 1.5px 0;
    vertical-align: top;
    width: 50%;
}

.info-lbl {
    font-size: 8.5px;
    font-weight: 700 !important;
    color: #000000;
    text-transform: uppercase;
    letter-spacing: .6px;
    display: block;
}

.info-val {
    font-size: 10.5px;
    font-weight: 700 !important;
    color: #000000;
}

/* ══════════════════════════════
   3. PRODUCTOS
══════════════════════════════ */
.products-header {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
    margin-bottom: 2px;
}

.products-header th {
    font-size: 8.5px;
    font-weight: 700 !important;
    letter-spacing: .8px;
    text-transform: uppercase;
    color: #000000;
    padding: 3px 0;
    border-bottom: 1px solid #111;
}

.products-header th.col-desc {
    text-align: left;
    width: auto;
}

.products-header th.col-qty,
.products-header th.col-pu,
.products-header th.col-imp {
    display: none;
}

.products-body {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
}

.products-body td {
    padding: 0;
    vertical-align: top;
    border-bottom: none;
}

.product-row td {
    padding: 4px 0 6px 0;
    border-bottom: 1px dashed #e8e8e8;
}

.products-body tr:last-child td {
    border-bottom: none;
}

.product-block {
    width: 100%;
}

.product-name-row {
    width: 100%;
    margin-bottom: 2px;
}

.p-name {
    display: block;
    width: 100%;
    font-size: 10px;
    font-weight: 700 !important;
    line-height: 1.15;
    word-break: normal;
    overflow-wrap: break-word;
    text-transform: uppercase;
}

.p-code {
    display: block;
    width: 100%;
    font-size: 7.8px;
    color: #555;
    font-family: 'DM Mono', monospace;
    font-weight: 700 !important;
    line-height: 1.05;
    margin-top: 1px;
}

.p-discount-row {
    font-size: 9px;
    color: #000000;
    margin-top: 2px;
    line-height: 1.8;
    display: flex;
    align-items: center;
    gap: 4px; /* espacio entre todos */
}

.p-original {
    text-decoration: line-through;
    color: #000000;
    font-family: 'DM Mono', monospace;
    font-weight: 700 !important;
}

.disc-tag {
    font-size: 10px;
    font-weight: 700 !important;
    background: #000000;
    color: #fff;
    padding: 0 2px;
    display: inline-block;
    line-height: 1.1;
}

.product-meta-row {
    width: 100%;
    display: table;
    table-layout: fixed;
    margin-top: 3px;
}

.product-meta-cell {
    display: table-cell;
    vertical-align: top;
}

.product-meta-cell.qty {
    width: 18%;
    text-align: left;
}

.product-meta-cell.pu {
    width: 36%;
    text-align: right;
    padding-right: 2mm;
}

.product-meta-cell.imp {
    width: 46%;
    text-align: right;
}

.meta-lbl {
    display: block;
    font-size: 7.5px;
    font-weight: 700 !important;
    text-transform: uppercase;
    letter-spacing: .4px;
    color: #444;
    margin-bottom: 1px;
}

.meta-val {
    display: block;
    font-size: 9.8px;
    font-weight: 700 !important;
    font-family: 'DM Mono', monospace;
    color: #000000;
    white-space: nowrap;
}

/* ══════════════════════════════
   4. TOTALES
══════════════════════════════ */
.totals-table {
    width: 100%;
    border-collapse: collapse;
}

.totals-table td {
    padding: 2px 0;
    font-size: 10.5px;
    font-weight: 700 !important;
    color: #000000;
}

.totals-table td.lbl {
    text-transform: uppercase;
    letter-spacing: .3px;
}

.totals-table td.amt {
    text-align: right;
    font-family: 'DM Mono', monospace;
    font-weight: 700 !important;
    white-space: nowrap;
}

.total-row td {
    padding-top: 6px;
    font-size: 15px;
    font-weight: 700 !important;
    color: #000000;
    letter-spacing: -.2px;
}

.total-row td.amt {
    font-size: 20px;
    font-family: 'DM Mono', monospace;
    letter-spacing: -1px;
    font-weight: 700 !important;
}

/* ══════════════════════════════
   5. PIE DE TICKET
══════════════════════════════ */
.pago-badge {
    display: inline-block;
    border: 1px solid #ccc;
    padding: 2px 7px;
    font-size: 9.5px;
    font-weight: 700 !important;
    text-transform: uppercase;
    letter-spacing: .4px;
    color: #000000;
}

.footer-msg {
    font-size: 11px;
    font-weight: 700 !important;
    letter-spacing: 2px;
    text-transform: uppercase;
    text-align: center;
    margin-top: 6px;
}

.footer-policy {
    font-size: 10px;
    color: #bbb;
    text-align: center;
    font-weight: 700 !important;
    line-height: 1.6;
    margin-top: 3px;
}
</style>
</head>
<body>
<div class="ticket">

  {{-- ══ 1. ENCABEZADO ══ --}}
  <div class="header">
    @if(file_exists(public_path('assets/img/maleri-ticket.png')))
      <img src="{{ public_path('assets/img/maleri-ticket.png') }}" class="logo" alt="Logo">
    @else
      <div class="brand-name">{{ strtoupper($empresa->nombre) }}</div>
    @endif

    @isset($empresa->slogan)
      <div class="brand-slogan">{{ $empresa->slogan }}</div>
    @endisset

    <div class="brand-meta">
      {{ strtoupper($empresa->direccion) }}<br>
      {{ strtoupper($empresa->ubicacion) }}<br>
      @if($empresa->telefono)Tel: {{ $empresa->telefono }} &nbsp;·&nbsp; @endif
      RFC: {{ $empresa->ruc }}
    </div>
  </div>

  <hr class="div-solid">

  {{-- ══ 2. INFORMACIÓN DE LA VENTA ══ --}}
  <div class="folio-wrap">
    <span class="folio-badge">{{ strtoupper($venta->comprobante->nombre) }} {{ $venta->numero_comprobante }}</span>
  </div>

  <table class="info-grid">
    <tr>
      <td>
        <span class="info-lbl">Fecha</span>
        <span class="info-val mono">{{ date('d/m/Y', strtotime($venta->fecha_hora)) }}</span>
      </td>
      <td>
        <span class="info-lbl">Hora</span>
        <span class="info-val mono">{{ date('H:i', strtotime($venta->fecha_hora)) }}</span>
      </td>
    </tr>
    <tr>
      <td style="padding-top:5px;">
        <span class="info-lbl">Cajero</span>
        <span class="info-val">{{ $venta->user->empleado->razon_social ?? $venta->user->name }}</span>
      </td>
      <td style="padding-top:5px;">
        <span class="info-lbl">Cliente</span>
        <span class="info-val">{{ strtoupper($venta->cliente->persona->razon_social) }}</span>
      </td>
    </tr>
    @if($venta->cliente->persona->rfc)
    <tr>
      <td colspan="2" style="padding-top:3px;">
        <span class="info-lbl">RFC Cliente</span>
        <span class="info-val mono">{{ $venta->cliente->persona->rfc }}</span>
      </td>
    </tr>
    @endif
  </table>

  <hr class="div-dash">

  {{-- ══ 3. DETALLE DE PRODUCTOS ══ --}}
  <table class="products-header">
    <tr>
      <th class="col-desc">Producto</th>
      <th class="col-qty">Cantidad</th>
      <th class="col-pu">Precio unitario</th>
      <th class="col-imp">Importe</th>
    </tr>
  </table>

  <table class="products-body">
    @foreach ($venta->productos as $detalle)
    @php
      $hasDiscount = ($detalle->pivot->descuento_porcentaje ?? 0) > 0;
      $precioOriginal = $detalle->pivot->precio_original ?? $detalle->pivot->precio_venta;
      $precioFinal = $detalle->pivot->precio_venta;
      $importe = $detalle->pivot->cantidad * $precioFinal;
    @endphp
    <tr class="product-row">
      <td>
        <div class="product-block">
          <div class="product-name-row">
            <span class="p-name">{{ strtoupper($detalle->nombre) }}</span>
            <span class="p-code">{{ $detalle->codigo }}</span>

            @if($hasDiscount)
              <div class="p-discount-row">
                <span class="disc-tag">-{{ number_format($detalle->pivot->descuento_porcentaje, 0) }}%</span>
                <span class="p-original">{{ number_format($precioOriginal, 2) }}</span>
                → {{ number_format($precioFinal, 2) }}
              </div>
            @endif
          </div>

          <div class="product-meta-row">
            <div class="product-meta-cell qty">
              <span class="meta-lbl">Qty</span>
              <span class="meta-val">{{ $detalle->pivot->cantidad }}</span>
            </div>

            <div class="product-meta-cell pu">
              <span class="meta-lbl">P.Unitario</span>
              <span class="meta-val">{{ number_format($precioFinal, 2) }}</span>
            </div>

            <div class="product-meta-cell imp">
              <span class="meta-lbl">Importe</span>
              <span class="meta-val">{{ number_format($importe, 2) }}</span>
            </div>
          </div>
        </div>
      </td>
    </tr>
    @endforeach
  </table>

  <hr class="div-dash">

  {{-- ══ 4. RESUMEN DE PAGO ══ --}}
  @php
    $descuentoAplicado = (float) ($venta->descuento_total_monto ?? 0);
    $subtotalAntesDescuento = (float) $venta->subtotal + $descuentoAplicado;
  @endphp

  <table class="totals-table">
    <tr>
      <td class="lbl">Subtotal</td>
      <td class="amt">{{ number_format($subtotalAntesDescuento, 2) }}</td>
    </tr>
    @if($descuentoAplicado > 0)
    <tr>
      <td class="lbl">Descuento aplicado</td>
      <td class="amt">-{{ number_format($descuentoAplicado, 2) }}</td>
    </tr>
    @endif
    <tr>
      <td class="lbl">{{ $empresa->abreviatura_impuesto }}</td>
      <td class="amt">{{ number_format($venta->impuesto, 2) }}</td>
    </tr>
  </table>

  <hr class="div-solid">

  <table class="totals-table">
    <tr class="total-row">
      <td class="lbl">Total</td>
      <td class="amt">{{ number_format($venta->total, 2) }}</td>
    </tr>
  </table>

  <div style="margin-top:8px; display:flex; justify-content:space-between; align-items:center;">
    <span class="pago-badge">{{ $venta->metodo_pago?->label() }}</span>
    @if(isset($venta->cambio) && $venta->cambio > 0)
      <span style="font-size:9.5px; font-weight:700 !important; color:#888; font-family:'DM Mono',monospace;">
        Cambio: {{ number_format($venta->cambio, 2) }}
      </span>
    @endif
  </div>

  <hr class="div-dash">

  {{-- ══ 5. PIE DE TICKET ══ --}}
  <div class="footer-msg">¡Gracias por su compra!</div>
  <div class="footer-policy">
    Consulta políticas en tienda.
  </div>

</div>
</body>
</html>