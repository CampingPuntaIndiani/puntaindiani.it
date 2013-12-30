<?php
    if (!(isset($GLOBALS['valid_req']) and $GLOBALS['valid_req'] === TRUE)) return; 

    Utils::load_dict();

?>
<? if ($_SESSION['lang'] === 'eng'): ?>
<h3>CAMPING REGULATIONS</h3>
<ol>
    <li>Management authorisation and legal registration is compulsory to access the camping site.</li>
    <li>The prices exhibited at the reception for the stay at the camping site are valid from arrival to 11.00 a.m. of the following day.</li>
    <li>To take possession of your tent or caravan pitch you must be authorised by the management.</li>
    <li>You can leave vehicles only in the places indicated by the management.</li>
    <li>Silence is essential from 11.00 p.m. to 8.00 a.m.; limit the noise during the day; television is tolerated only if at very low volume.</li>
    <li>Guests must take care of their belongings; the management declines all responsibilities for any theft or damage except for the objects that have been left in custody with regular receipt.</li>
    <li>The vehicles inside the tourist complex must not exceed the speed of 5 km/h.</li>
    <li>You must respect the vegetation, the hygiene and the cleanliness of the site.</li>
    <li>Dog entrance must be pre-authorized by the Management</li>
    <li>Under the authorisation of the management you can connect to the proper sockets necessary but only for lighting and fridge which must be earthen. Tents are not entitled to power supply.</li>
    <li>No visits are allowed (see article 1). In case of authorised visits, they are subject to the price list.</li>
    <li>After consideration by the management, the non-observance of these rules or a behaviour that ruins the harmonious atmosphere of the camping site may result in expulsion as an unwelcome guest.</li>
</ol>
<? elseif ($_SESSION['lang'] === 'ita'): ?>
<h3>REGOLAMENTO INTERNO</h3>
<ol>
    <li>Per l'ingresso al Camping &egrave; obbligatoria l'autorizzazione della direzione e relativa registrazione di legge.</li>
    <li>Le tariffe di soggiorno camping esposte alla ricezione vanno dall'arrivo fino alle ore 11 del giorno successivo.</li>
    <li>L'occupazione del posto tenda o caravan &egrave; subordinata all'autorizzazione della direzione.</li>
    <li>Lo stazionamento dei veicoli &egrave; permesso negli spazi indicati dalla direzione.</li>
    <li>Dalle ore 23 alle ore 8 &egrave; prescritto il silenzio, moderazione dei rumori durante il giorno la televisione &egrave; tollerata soltanto se a bassissimo volume sonoro.</li>
    <li>Gli ospiti dovranno avere cura degli oggetti di loro propriet&agrave;, la direzione non &egrave; responsabile degli eventuali furti o danneggiamenti salvo che per gli oggetti affidati ad essa ed accettati in consegna con rilascio di ricevuta.</li>
    <li>La velocit&agrave; dei veicoli all'interno del complesso turistico non deve superare i 5 km orari.</li>
    <li>E' obbligatorio rispettare la vegetazione l'igiene e la pulizia del complesso.</li>
    <li>Cani ammessi previo accordo con la direzione.</li>
    <li>L'allacciamento elettrico alle apposite prese che si dovesse ritenere necessario, previa autorizzazione della direzione, &egrave; consentito soltanto per l'illuminazione e apparecchio frigorifero e deve essere eseguito con scarico a terre di sicurezza. Le tendine (extra) sono escluse dall'allacciamento.</li>
    <li>Non sono ammesse la visite (vedi articolo 1). Eventuali visite autorizzate saranno soggette a listino prezzi.</li>
    <li>A giudizio della direzione la mancata osservazione di tali norme ed un comportamento che danneggi l'armonia e lo spirito dell'insediamento, potr&agrave; comportare l'allontanamento dal camping, come ospite indesiderato.</li>
</ol>
<? elseif ($_SESSION['lang'] === 'ned'): ?>
<h3>CAMPING REGLEMENT</h3>
<ol>
    <li>Voor de toegang tot de camping zijn de toestemming van de directie en bijbehorende wettelijke registratie vereist.</li>
    <li>De verblijfstarieven van de camping, die bij de receptie zijn opgehangen, gelden vanaf de dag van aankomst tot aan 11:00 uur van de volgende dag.</li>
    <li>Het bezetten van een staanplaats voor tent of caravan is afhankelijk van de toestemming van de directie.</li>
    <li>Het parkeren van vervoersmiddelen egestaan, na verkregen toestemming van de direktie. Een klein tentje wordt niet van elektriciteit voorzien.</li>
    <li>Van 23:00 uur tot 8:00 uur is stilte voorgeschreven, overdag gematigd geluid, de televisie mag alleen zachtjes aan.</li>
    <li>De gasten word verzocht zelf op hun bezittigen te letten. De directie is niet aansprakelijk voor eventuele diefstal of schade, behalve als  die bezittigen ter bewaring waren afgegeven tegen een ontvangstbewijs.</li>
    <li>De snelheid van voertuigen op het hele kampeerterrein mag de 5 km per uur niet overschrijden.</li>
    <li>Men is verplicht de groenzones, de hygi&#235;ne en properheid van de inrichting te respecteren.</li>
    <li>De aankomst moet worden goedgekeurd door de direktie.</li>
    <li>Aansluiting op het elektriciteitsnet aan de daarvoor bedoelde stopcontacten is alleen met geaarde stekkers voor verlichting en ijskast toegestaan, na verkregen toestemming van de direktie. Een klein tentje (extra) wordt niet van elektriciteit voorzien.</li>
    <li>Bezoek is niet toegestaan (zie artikel 1). Voor eventueel toegestaan bezoek gelden de tarieven volgens de prijslijst.</li>
    <li>Als deze regels volgens de directie niet worden nageleefd en als het gedrag de verstandhouding en de geest van de inrichting schaadt, kan dit leiden tot verwijdering van de camping als ongewenste gast.</li>
</ol>
<? elseif ($_SESSION['lang'] === 'ger'): ?>
<h3>HAUSORDNUNG</h3>
<ol>
    <li>Der Aufenthalt im Campingplatz ist nur mit Genehmigung der Direktion bzw. nach der gesetzlich vorgeschriebenen Anmeldung gestattet.</li>
    <li>Die im Empfangsbuero ausgehaengten Tarife gelten ab Ankunft bis um 11 Uhr des darauffolgenden Tages.</li>
    <li>Ein Zelt- oder Wohnwagenplatz darf nur mit Genehmigung der Direktion belegt werden.</li>
    <li>Das Parken von Fahrzeugen ist auf den von Direktion angegebenen Plaetzen gestattet.</li>
    <li>Von 23 bis 8 Uhr ist Ruhe geboten. Im interesse Nachbarn bitte wir generell so wenig Laerm wie moeglich zu machen und Radio bzw. Fernsehen leise zu hoeren.</li>
    <li>Unsere Gaeste muessen fuer Ihr Eigentum selbst Sorge tragen. Wir uebernehmen keine Haftung fuer Diebstaehle oder Beschaedigungen, mit Ausnahme der in Verwahrung genommenen Gegenstaende.</li>
    <li>Im Campingplatzgelaende gilt eine Hoechstgeschwindigkeit von 5 km/h.</li>
    <li>Auf die notwendige Sauberkeit und Hygiene ist zu achten. Campingeinrichtungen und-bepflanzungen sind pfleglich zu behandeln.</li>
    <li>Fuer das Mitbringen des Hundes auf dem Campingplatz ist die Zustimmung der Direktion erforderlich.</li>
    <li>Die Steckdosen duerfen nur nach Genehmingung durch die Direktion fuer Beleuchtung und Kuehlgeraete mit sicherheitstechnisch vorgeschriebener Erdung benutz werden. Fuer kleine Zelte (extra) ist der Stromanschluss verboten.</li>
    <li>Besuche nicht erlaubt (siehe Punkt 1). Eventuell werden die Besuche  laut normaler Preisliste berechnet.</li>
    <li>Es liegt im Ermessen der Direktion, bei Nichbeachtung dieser Platzordnung oder bei einem die Gemeinschaft stoerenden Verhalten, eine Verweisung von dem Camping auszusprechen.</li>
</ol>
<? endif ?>

<?=join($GLOBALS['dict']->page->{$_SESSION['lang']}->pet_disclaimer, '')?>
