# Mi Proyecto
# Instalacion y despliegue del proyecto
 
1. Situate en la raiz del proyecto
2. Variables:
    ```sh 
    cp environments/.env.develop.env ./.env
    ```
2. Si tienes docker ejecuta:
    ```sh
    docker compose up -d
    ```
3. Una vez terminado esperamos unos segundos que ejecute el script de iniciacion que esta en la carpeta script en la raiz del proyecto

4. Una vez que termine vamos a [interfaz](http://localhost:8050/) y veremos la interfaz

5. Si queremos ver la api rest y probarla al completo usaremos [api](http://localhost:8050/docs/api#/)
