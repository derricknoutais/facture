<img src="{{ $message->embed( asset('/uploads/'. $company->paramètres->logo )) }}" width="200px">

<h1> Rappel de Paiement </h1>

<p style="margin-top: 2%">Très chèr(e) Mr/Mme {{ $client->nom . ' ' . $client->prénom }},</p>

<p>Ce message vous est envoyé pour vous rappeler qu'à ce jour votre compte avec {{ $company->name }} s'élève à {{ $client->solde() }} F CFA.</p>

<p style="margin-top: 2%">Merci de bien vouloir vous rapprocher de nos agents afin d'en savoir plus.</p>

<p style="margin-top: 2%">En Attente d'une suite favorable, </p>
<p>veuillez agréer nos salutations distinguées</p>


