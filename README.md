![Cinema Hesperia](https://github.com/scrothalos/Progetto-tec-web/blob/master/images/scritta_cinema.png?raw=true)

## Struttura database

#### `film`
| Campo		| Tipo			| Attributi		|
| --------- | ------------- | ------------- |
| cod		| int			| PRIMARY KEY	|
| titolo	| varchar(100)	| NOT NULL		|
| genere	| varchar(100)	| 				|
| durata	| int			| NOT NULL		|
| anno		| year			| 				|
| regia		| varchar(100)	| 				|
| cast		| varchar(200)	| 				|
| trama		| longtext		| NOT NULL		|
| immagine	| longtext		| NOT NULL		|

#### `programmazione`
| Campo		| Tipo			| Attributi		|
| --------- | ------------- | ------------- |
| cod		| int			| PRIMARY KEY	|
| film		| int			| FOREIGN KEY	|
| data		| date			| NOT NULL		|
| ora		| time			| NOT NULL		|
| sala		| varchar(20)	| FOREIGN KEY	|

#### `prenotazione`
| Campo		| Tipo			| Attributi		|
| --------- | ------------- | ------------- |
| proiezione| int			| FOREIGN KEY	|
| utente	| varchar(30)	| FOREIGN KEY	|
| posti		| int			| NOT NULL		|

#### `sala`
| Campo		| Tipo			| Attributi		|
| --------- | ------------- | ------------- |
| nome		| varchar(20)	| PRIMARY KEY	|
| posti		| int			| NOT NULL		|

#### `utente`
| Campo		| Tipo			| Attributi		|
| --------- | ------------- | ------------- |
| username	| varchar(30)	| PRIMARY KEY	|
| nome		| varchar(30)	| NOT NULL		|
| cognome	| varchar(30)	| NOT NULL		|
| email		| varchar(40)	| CANDIDATE KEY	|
| password	| longtext		| NOT NULL		|


## Idee relazione:
- Connessione al database tramite prepared statements per migliorare la sicurezza, nelle pagine di login, registrazione e cambio password (che sono tutti i posti dove l'utente inserisce stringhe)
- Controllo degli accessi a pagine riservate da parte di utenti non loggati
- Tutti i parametri passati tramite form/GET/POST vengono controllati col sanitizer
- Se la registrazione fallisce, l'utente viene rimandato alla pagina di registrazione e i parametri precedentemente inseriti vengono inclusi tramite GET, tranne la password che non viene passata per motivi di sicurezza (update: anche il login manda indietro il nome)
- Approccio modulare nei file php per migliorare la manutenibilità
- Credenziali:
  - `mariorossi` : `password`
  - `utente` : `utente`
  - `user` : `user`
  - `admin` : `admin`
- Struttura dei file
