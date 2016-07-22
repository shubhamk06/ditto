    <?php if (ditto::checkSession(true) === true): ?>
      <div class="ribbon mini dark">
        <div class="container">
          <a href="/dash/">Go To Your Dashboard</a>

          <br><br>

          <a href="/account/" class="button fixed">
            Manage Account
          </a>
          <a href="/logout/" class="button fixed">
            Log Out
          </a>
        </div>
      </div>
    <?php endif; ?>
    <div class="ribbon mini dark">
      <div class="container">
        All of the code running ditto is available to the public on
        <a href="https://github.com/zbee/ditto/" target="_blank">GitHub</a>.
        <br><br>
        Created in 2016, public domain, by
        <a href="https://keybase.io/zbee">Ethan Henderson (zbee)</a>.
      </div>
    </div>
    <div class="ribbon mini dark">
      <div class="container">
        <a href="/about/">About ditto</a>,
        <a href="/about/#why">Why it was made</a>,
        <a href="/about/#principles-freedom">Why it is free</a>,
        <a href="/about/#how">How it works</a>,
        <a href="/about/#effectivenes">Effectiveness</a>,
        <a href="/about/#accuracy">Accuracy</a>,
        <a href="/about/#collected">Indexes formed</a>,
        <a href="/about/#reported">Data reported</a>,
        Privacy Policy, and
        <a href="/about/legal/terms/">Terms of Use</a>
      </div>
    </div>

    <script>
      var drawChart = function () {
        if (typeof drawMood == "function") {
          drawMood();
        }
        if (typeof drawSleep == "function") {
          drawSleep();
        }
        if (typeof drawFood == "function") {
          drawFood();
        }
      };
    </script>
  </body>
</html>