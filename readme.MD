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

# Probleem oplossen

Om het probleem op te lossen zijn er drie wegen te gaan. In volgorde van gewenstheid:

1. Verwijder de inline styling en verplaats deze naar een CSS-bestand
2. Maak een hash-waarde van de `H1`-tag style en vermeld deze als in de CSP
3. Pas de CSP aan zodat inline styles toegestaan kunnen worden.

## Verwijder de styling naar CSS-bestand

Maak een nieuw CSS-bestand (bijvoorbeeld `css/index.css`) en verwijs er naar in de `<heade>` van de HTML in
[index.html](./web/src/index.html).

```html

<head>
    <link rel="stylesheet" href="css/csp_logs.css">
</head>
```

Verwijder vervolgens de `style` attribuut uit het `h1` element. Dit 
```html
<body>
  <h1>To-Do Lijst</h1>
  <ul id="todo-list"></ul>
  <p>
    <a href="csp_logs.php">Content Security Policy Report</a>
  </p>
</body>

```

## Maak een hash waarde van het H1-element

Gegeven de HTML:

```html
<h1 style="color:blue">To-Do Lijst</h1>
```

is tekst waar je een Hash voor moet maken is "color:blue". In Windows kan dat met Powershell. 

```powershell
  $style = "color:blue;"
  $hash = [System.Convert]::ToBase64String([System.Security.Cryptography.SHA256]::Create().ComputeHash([System.Text.Encoding]::UTF8.GetBytes($style))) "sha256-$hash"

```

In Linux kun je dat zo doen: 

```bash
echo -n "color:blue;" | openssl dgst -sha256 -binary | openssl base64
```

Dit levert de volgende hash op:

```text
7HRnbu34a0L9o5dtOASt+1qjJ5vmVdFjxOnMidDCX+4=
```

Dit moet opgenomen worden in de CSP. In onderstaande voorbeeld wordt slechts een deel van de totale configuratie getoond!
Let op: we gebruiker hier een hash en niet een `nonce`. Zie ook de referenties onderaan.

```apacheconf
Content-Security-Policy "style-src 'self' 'sha256-7HRnbu34a0L9o5dtOASt+1qjJ5vmVdFjxOnMidDCX+4='"

```

## Pas de CSP aan zodat inline styles toegestaan kunnen worden

Dit is de meest ongewenste. Dit betekent namelijk dat je eigenlijk zegt 'ik pas geen beveiliging toe op stijl-elementen'. 
Dit doe je door een `unsafe-inline` sleutelwoord toe te voegen:

```apacheconf
Content-Security-Policy "style-src 'self' 'unsafe-inline'"

```



# Referenties

* [MDN Content-Security-Policy style-src Nonce](https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Headers/Content-Security-Policy/style-src)
* [MDN Content-Security-Policy style-src Hash](https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Headers/Content-Security-Policy#hash_algorithm-hash_value)
* 