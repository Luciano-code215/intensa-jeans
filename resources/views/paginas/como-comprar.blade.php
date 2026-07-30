@extends('layouts.app')

@section('title', 'Cómo Comprar - Intensa Jeans')

@section('content')
    <div class="container my-5">
        <div class="text-center mb-5">
            <h1 class="font-titulo fw-bold text-denim">¿Cómo Comprar?</h1>
            <div class="mx-auto" style="width: 60px; height: 3px; background-color: #d4af37;"></div>
        </div>

        <div class="row g-4 justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                    <p class="text-secondary lh-lg mb-4">
                        En <strong class="text-denim">Intensa Jeans</strong> queremos que tu experiencia de compra sea
                        simple, segura y cercana. Por eso no tenemos un carrito de pago automático — preferimos
                        coordinar todo con vos, como siempre.
                    </p>

                    <div class="d-flex gap-3 mb-4">
                        <div class="bg-denim text-white rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                            style="width: 40px; height: 40px; font-weight: bold;">1</div>
                        <div>
                            <h5 class="fw-bold text-denim mb-1">Elegí tus productos</h5>
                            <p class="text-secondary small mb-0">Navegá por nuestro catálogo, seleccioná los jeans que más te gusten
                                y agregalos al carrito. Podés elegir talle y cantidad.</p>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mb-4">
                        <div class="bg-denim text-white rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                            style="width: 40px; height: 40px; font-weight: bold;">2</div>
                        <div>
                            <h5 class="fw-bold text-denim mb-1">Completá tus datos</h5>
                            <p class="text-secondary small mb-0">Ingresá tu nombre y teléfono (o iniciá sesión si ya tenés cuenta).
                                No manejamos pagos en la web por seguridad.</p>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mb-4">
                        <div class="bg-denim text-white rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                            style="width: 40px; height: 40px; font-weight: bold;">3</div>
                        <div>
                            <h5 class="fw-bold text-denim mb-1">Recibí tu confirmación</h5>
                            <p class="text-secondary small mb-0">Te va a llegar un mensaje a tu WhatsApp con el detalle del pedido
                                y el total. El dueño recibe una copia automática.</p>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mb-4">
                        <div class="bg-denim text-white rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                            style="width: 40px; height: 40px; font-weight: bold;">4</div>
                        <div>
                            <h5 class="fw-bold text-denim mb-1">Coordinamos el pago y el envío</h5>
                            <p class="text-secondary small mb-0">Te contactamos por WhatsApp para acordar el método de pago
                                (efectivo, transferencia o tarjeta) y la forma de entrega o envío.</p>
                        </div>
                    </div>

                    <div class="bg-light rounded-3 p-4 mt-4">
                        <p class="mb-0 text-secondary small">
                            <i class="bi bi-shield-check text-success me-2"></i>
                            <strong>Importante:</strong> No realizamos cobros automáticos en la web. Todos los pagos se coordinan
                            directamente con nuestro staff para tu tranquilidad. Ante cualquier duda, escribinos por WhatsApp.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection