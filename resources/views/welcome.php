<h1>Welcome {{user.nombre}}</h1>
<p>{{text}}</p>
<p>{{text2.pos1}}</p>
<a href="/panel">Panel</a>

{{#debo_pintar_mensaje}}
<p>Debo pintar mensaje</p>
{{/debo_pintar_mensaje}}

{{^debo_pintar_mensaje}}
<p>No Debo pintar mensaje</p>
{{/debo_pintar_mensaje}}

<?php echo $GLOBALS['lalala'];?>
