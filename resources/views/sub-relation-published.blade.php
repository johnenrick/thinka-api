<html>
  <body>
  <p>
    Hello {{$username}},
  </p>
  <p>
    {{$notificationMessage}}
  </p>
  <p>
    <a href="https://staging.thinka.io/#/branch/{{$parentRelationId}}/t/{{$kebabStatement}}#{{$relationId}}">
      "{{$statementText}}"
    </a>
  </p>
  <p style="background-color: whitesmoke">
    <small>This is an automated message. Do not reply</small>
  </p>
  </body>
</html>