    <?php if (ditto::checkSession(true) === true): ?>
      <div class="ribbon mini dark">
        <div class="container">
          <a href="/logout/" class="button">
            Would you like to log out?
          </a>
        </div>
      </div>
    <?php else: ?>
      <div class="ribbon dark">
        <div class="container">
          <h1>Made by Ethan Henderson (Zbee)</h1>
          I can by contacted via email at
          <a href="mailto:ethan@zbee.me" target="_blank">ethan@zbee.me</a>
          and encryption can be added with my
          <a href="https://keybase.io/zbee/" target="_blank">PGP public key</a>.
          <br><br>
          Everything here is free, and will remain free.
          Ditto was created only to help.
          <br><br>
          The low mood index comes from the results of the
          <a href="http://www.cqaimh.org/pdf/tool_phq9.pdf" target="_blank">
          PHQ-9 questionnaire</a>.
        </div>
      </div>
    <?php endif; ?>
    <div class="ribbon mini dark">
      <div class="container">
        All of the code running ditto is available to the public on
        <a href="https://github.com/zbee/ditto/" target="_blank">GitHub</a>.
        <br><br>
        Created in 2016, public domain.
      </div>
    </div>
  </body>
</html>