@component('mail::message')
# Su Certificado contra Accidentes de Transito está vencido!!!.
SR(A):
@if($cat->vehiculo->persona)
{{$cat->vehiculo->persona->nombre}}
@else
{{$cat->vehiculo->empresa->nombre}}
@endif
<br>Estamos al tanto de su Certificado y nos preocupa decirle que su Certificado contra Accidentes de Transito
{{$cat->numero}} está vencido desde el día {{$cat->fin_certificado}}.
<br>Esperamos se ponga en contacto con nosotros en  nuestras oficinas para tramitar
la renovación de su Certificado Contra Accidentes de Transito y así pueda continuar con la seguridad que le brindamos
a usted y sus pasajeros.

Gracias por su atención,<br>
AFOCAT Regional León de Huánuco
@endcomponent
