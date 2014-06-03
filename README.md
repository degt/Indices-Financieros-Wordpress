Indices Financieros Wordpress
=============================

Plugin para obtener indices financieros Chilenos. Dólar, UF, UTM, ipsa, ipc, etc.



# ¿Como implementarlo?

A través del Shortcode 'indices'

Ejemplo:

```php
<?php echo do_shortcode( '[indices]' ); ?>
```

# Datos disponibles

"bovespa","cobre","dolar-obs","dolar-us","euro","ftse","gas-natural","ibex","imacec","ipc","ipsa","ivp","nikkei","oro","peso-arg","petr-brent","petr-wti","plata","real-bras","uf-hoy","utm","yen-jap"

# Importante

El template debe tener wp_head(); 


# Log

*2.0 Se agregan más datos
	Se cambia la fuente
*1.0 Lee los datos de Terra.cl