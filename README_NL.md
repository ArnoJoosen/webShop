# WebShop

## Beschrijving
Dit is een eenvoudig webshop-project. Het is een schoolproject voor het vak Webprogrammeren. Het project is geschreven in PHP, JavaScript, CSS en HTML.

## Installatie
### Installatie met Docker
1. Maak een clone van de repository
2. Controleer of er een map genaamd **logs** in de `src` map aanwezig is. Zo niet, maak er dan een aan.
3. Voer de volgende commando uit in de hoofdmap van het project:
```
docker-compose up --build -d
```
4. Open de browser en ga naar [http://localhost:8080](http://localhost:8080) om het project te bekijken (om phpmyadmin te bekijken, ga naar [http://localhost:8081](http://localhost:8081))
5. Het project draait nu
   (Om het project te debuggen, kun je xdebug in vscode gebruiken.)
6. Om het project te stoppen, voer je het volgende commando uit in de hoofdmap van het project:
```
docker-compose down
```
### Installatie zonder Docker
1. Maak een clone van de repository
2. Installeer een webserver (bijvoorbeeld Apache)
3. Installeer een database server (bijvoorbeeld MySQL)
4. Maak een database aan en importeer het SQL-bestand uit de `db-setup.sql`
5. Wijzig de databaseverbinding in het bestand `src/config.php`
6. Kopieer de **src** map naar de webserver
7. Controleer of er een map genaamd **logs** in de `src` map aanwezig is. Zo niet, maak er dan een aan.
8. Controleer of er een map genaamd **uploads** in de `src` map aanwezig is. Zo niet, maak er dan een aan.
9. Controleer of er een map genaamd **products** in de `src/uploads` map aanwezig is. Zo niet, maak er dan een aan.
10. Controleer of er een map genaamd **categories** in de `src/uploads` map aanwezig is. Zo niet, maak er dan een aan.

## Gebruik
Het project is een eenvoudige webshop. Je kunt je registreren, inloggen, producten aan je winkelwagentje toevoegen en ze kopen.
### Pagina's
- Klantzijde: server/index.php (docker: [http://localhost:8080](http://localhost:8080))
- Beheerzijde: server/admin/login.php (docker: [http://localhost:8080/admin/login.php](http://localhost:8080/admin/login.php))
### Klantfuncties
- Registreren
- Inloggen
- Producten aan winkelwagentje toevoegen
- Producten kopen
- Bestelgeschiedenis bekijken
- Account verwijderen
- Zoeken naar producten
- Klikken door categorieën
- Recensies toevoegen en bekijken
- Website thema veranderen
### Beheerfuncties
- Producten toevoegen
- Inloggen
- Producten bewerken
- Producten verwijderen
- Alle bestellingen bekijken
- Bestelstatus wijzigen
- Categorieën toevoegen
- Categorieën bewerken
- Categorieën verwijderen als er geen producten of subcategorieën in zitten
- Gebruikers en bestelgeschiedenis bekijken
- Beheerpagina geeft een overzicht van de bestellingen en gebruikers
- Website thema veranderen
### SAdmin functies
- Alle beheerfuncties
- Beheerders en SAdmins toevoegen
## Database diagram
![Database diagram](./Databases_ERd.jpg)
