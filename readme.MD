# Web Security

Deze eenvoudige website is opgedeeld in een eenvoudige API en een Front-end. De Front-end roept de API aan om gegevens
op te halen.

Om te zorgen dat e.e.a. werkt is een zogenaamde Content-Security-Policy (CSP) nodig voor de webpagina van de Front-End. 

De API probeert gegevens te beveiligen door te werken met een Cross-Origin Resource Sharing policy (CORS).

De CSP is te vinden in het bestand [.htaccess](./web/src/.htaccess).

De CORS configuratie staat in de [api](./api/index.php).

In de file [index.html](./web/src/index.html) staat nu een niet toegestane `<style>`-tag in het `<h1>`-element. De 
CSP zal daarom de styling niet toepassen en deze fout rapporteren aan de website (via de URL `csp_report.php`). De 
gerapporteerde fouten kunnen worden ingezien op de pagina `csp_logs.php`. Er is een link op de home pagina.

# Testen

Om de website te testen start je de docker containers (`docker compose up`) en navigeer je naar 
[de homepage](http://localhost:8080/). 