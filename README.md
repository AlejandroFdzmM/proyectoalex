# ProyectoAlex
Proyecto final 2ºASIR
IES Iliberis


# Proceso de despliegue

## Pasos previos
Tener instalado previamente aws-cli, terraform, y kubenetes-cli



## Despliegue 

- Introducir las credenciales de Amazon en la el archivo credentials en la carpeta .aws
- Modificar el null resource segun la ubicacion de tus archivos, modificar el role_arn segun el tuyo tanto en eks como en node group

## Configuraciones

### Ldap
Introducimos los archivos en el pod
- kubectl cp admin.ldif pod-ldap:/
- kubectl cp ou.ldif pod-ldap:/
Entramos en el pod
- kubectl exec -it pod-ldap bash
Una vez dentro del pod lanzamos el comando para añadir la uo y el usuario admin
- ldapadd -x -D "cn=admin,dc=proyectoalex,dc=com" -W -f ou.ldif
- ldapadd -x -D "cn=admin,dc=proyectoalex,dc=com" -W -f admin.ldif

### MYSQL

Copia el archivo dump al pod
- kubectl cp mysqldump.sql pod-mysql:/
Entramos al pod el cual ya tiene la base de datos creada por su yaml y inyectamos los datos
- kubectl exec -it pod-mysql bash
- mysql -u root -p alejandro < mysqldump.sql
Procedemos a crear los usuarios con los permisos necesarios para comunicarse con prometheus nginx
- mysql -u root -p alejandro
- CREATE USER 'exporter'@'%' IDENTIFIED BY 'exporter' WITH MAX_USER_CONNECTIONS 3;
- GRANT PROCESS, REPLICATION CLIENT, SELECT ON *.* TO 'exporter'@'%';
- CREATE USER 'nginx_user'@'%' IDENTIFIED BY 'secret';
- GRANT ALL PRIVILEGES ON alejandro.* TO 'nginx_user'@'%';
- FLUSH PRIVILEGES;

### Nginx
Este pod es una modificacion de un pod php-fpm con las pdo de mysql y ldap
Acceder a la carpeta con los archivos y copiarlos en el deployment nginx posteriormente entrar al pod y modificar dueño
- kubectl cp . nginx-pod:/var/www/html/
- kubectl exec -it nginx-phpfpm-deployment-549f97d7c-8b5bt bash
- chown -R www-data:www-data /var/www/html

### Prometheus
Deberia estar funcionando ya tiene una alerta activa Watch dog para comprobar que funciona correctamente
Se puede acceder al pod mediante load balancer o mediante un:

- kubectl port-forward promethesus-pod 9090:9090

Por defecto en esta configuracion debes acceder mediante el port forward

### Grafana
Conectar grafana a prometehus

Conennections > Data Source > Add new data source 

En Prometheus server URL * introducir el nombre del servico promethesu seguido del puerto

- http://prometheus-service:9090

En esta config esta con un servicio LoadBalancer, modificar al gusto

Algunos dasboard que me funcionan son los 14057 1860


### Adicional

En mi caso he connectado el balanceador de carga de nginx a las ip de Cloudflare con politicas para redirifir de http a https
En caso de que no se quiera conectar a Cloudflare modificar el servicio de nginx o comentar las lineas de las ips






