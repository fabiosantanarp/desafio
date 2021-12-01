<p> Prezado, <b>{{ $informationPayee["firstName"] ?? $informationPayee["corporateName"] }} </b></p>

<p> Você acaba de receber uma transferência de  {{ $informationPayer["firstName"] ?? $informationPayer["corporateName"] }} </p>

<p>Valor: {{ $notificationData["operationValue"] }}.

<p>Equipe Desafio</p>
