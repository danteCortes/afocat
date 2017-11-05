@component('mail::message')
# Su Certificado contra Accidentes de Transito está a punto de vencer.
SR(A):
@if($cat->vehiculo->persona)
{{$cat->vehiculo->persona->nombre}}
@else
{{$cat->vehiculo->empresa->nombre}}
@endif
<br>Por que nos preocupamos por su seguridad, la de su familia y sus pasajeros, AFOCAT Regional León de Huánuco le recuerda que su Certificado
Contra Accidentes de Transito vence el día {{$cat->fin_certificado}}, por tal motivo le invitamos a acercarse a nuestras oficinas para tramitar
la extención de su Certificado Contra Accidentes de Transito y así pueda continuar con la seguridad que le brindamos a usted y sus pasajeros.

Gracias por su atención,<br>
AFOCAT Regional León de Huánuco
@endcomponent
