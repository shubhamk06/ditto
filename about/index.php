<?php
require("../assets/php/header.php");
?>

<div class="ribbon">
  <div class="container">
    <h1 class="bigbold">ditto</h1>

    <p>
      <b>
        A machine-learning low mood tracker and correlator made to help
        fix low mood the best you can <abbr title="There is nothing
        wrong with medication in my opinion, I view it as a necessity.
        However, medication requires the courage to seek help. Something that
        can be very hard to build up.">without medication</abbr>, produced in
        2016 by
        <br>
        <a href="https://keybase.io/zbee">Ethan Henderson (zbee)</a>.
      </b>
    </p>

    <p>
      By regularly collecting data -both from the patient, and services used-
      ditto is able to track low mood, suicidality, parts of life that play
      into low mood (such as sleep and eating), and
      effectiveness of medication.
    </p>
    <p>
      With these all being tracked together, ditto is also able to form and
      monitor correlations automatically (using machine-learning), as well as
      suggest changes to improve low mood.
    </p>
    <p>
      Finally, ditto is also able to help the patient-doctor information
      coherency and flow by generating reports that provide more insight into
      the patient's life than at-the-appointment forms always can.
    </p>
  </div>
</div>

<div class="ribbon">
  <div class="container">
    <h1 id="why">Why was ditto made?</h1>

    <p>
      <b>
        Ultimately, ditto was created to help prevent the loss of life through
        tracking, correlation, suggesting fixes, and reporting.
      </b>
    </p>

    <h3 id="why-track">1: Track</h3>
    <p>
      Ditto is designed as a low mood tracker as the central focus, but also
      tracks other parts of life that tend to play into low mood, such as
      eating and sleeping.
    </p>
    <p>
      By consistently tracking low mood and the parts of life that contribute
      to it (by patient input or services used) ditto can much more easily
      perform its primary goal of helping to fix low mood the best you can
      without medication (ie alone).
    </p>

    <h3 id="why-correlate">2: Correlate</h3>
    <p>
      Ditto is made to learn automatically from patients' data input so as to
      form correlations between low mood and the parts of our lives that play
      into it.
    </p>

    <h3 id="why-suggest">3: Suggest Fixes</h3>
    <p>
      While correlation is not causation, it sure is a hint. By observing
      correlations on an individual and wide-scale basis, the correlation can
      be studied out, and suggestions can be made to help eliminate elements
      that play into low mood so as to improve it.
    </p>

    <h3 id="why-report">4: Report</h3>
    <p>
      Ditto does not share any information with anyone (and in-fact makes
      quite certain that no one can take it either:
      <a href="#security">security</a>), it can generate reports at the
      user's request to share with a mental health professional.
    </p>
    <p>
      These reports can help the mental health professional look deeper into
      the patient's life without the need for long "track this, track that"
      phases between appointments and with greater continuity in order to
      keep on top of low mood.
    </p>
    <p>
      <b>Disclaimer:</b> ditto does not currently share any information with
      anyone for any reason. This may change once suicidality becomes more
      easily determined by ditto, and professional relationships exist with
      chosen mental health providers (and/or emergency contacts) in order to
      prevent possible loss of life.
    </p>
    <br>
    <small>
      <a onClick="$('#why-i').toggle()">(why I decided make ditto)</a>
    </small>
    <p style="display:none" id="why-i">
      After losing numerous friends to suicide, maintaining friendships with
      those who need help and don't quite have the courage to seek it, and
      dealing with low mood, suicidality, and self-harm myself I desperately
      wanted to do everything in my power to help everyone who struggles
      through what I and my friends do on a daily basis.
      <br><br>
      We all need help from time to time, and some of us more-so than others.
      It's okay. I understand that it can be hard to seek that help though;
      ditto is here to try everything it can figure to do to help us improve
      our moods and help us be okay.
    </p>
  </div>
</div>

<div class="ribbon">
  <div class="container">
    <h1 id="how">How it works</h1>

    <p>
      <b>
        Ditto works by collecting data, looking for correlations, providing
        suggestions, and reporting data.
      </b>
    </p>

    <p>
      Ditto collects data both from the user and popular services that the
      user utilizes (such as Apple Health and Google Fit) on a regular basis
      to provide a continuous flow of data so as to always be in a position
      to help.
    </p>
    <p>
      Ditto then utilizes simplistic machine-learning (the kind that can be
      made by a non-genius eighteen-year-old) to identify correlations between
      low mood an the parts of life that play into it.
    </p>
    <p>
      Using these correlations, ditto can make suggestions on how low mood
      can possibly be improved upon.
    </p>
    <p>
      If professional help is sought, ditto can also produce reports for the
      mental health professional that can help them gain a deeper, easier
      view into their patient's life.
    </p>
    <p>
      Additionally, ditto can also use correlations found and user's feedback
      on the suggested fixes to help further mental healthcare.
    </p>
  </div>
</div>

<div class="ribbon">
  <div class="container">
    <h1 id="principles">Principles</h1>

    <p>
      <b>
        Ditto is free, secure, private, and only interested in helping patients.
      </b>
    </p>

    <h3 id="principles-freedom">Freedom</h3>
    <p>
      As part of only being interested in helping patients (users), ditto is
      free.
    </p>
    <p>
      I believe people in need should never have to pay a dime to receive
      help, and I'm applying that belief to ditto and the help it can provide.
    </p>
    <p>
      As part of my belief that you shouldn't need to pay to receive help,
      willing data on correlations
      (<a href="/about/legal">completely unidentifiable</a>,
      <a href="/about/#opting">completely optional</a>) is
      <a href="/data/">published</a> for free to hopefully help further
      research and healthcare.
      The code running ditto is also
      <a href="https://github.com/zbee/ditto">published</a> for free to
      hopefully help people improve upon it or create other or better
      healthcare software.
    </p>
    <p>
      In union with the previous, the user is also totally free to do what
      they like with their data from their life. If you do not want your data
      shared (even with promise of it being totally impossible to link to
      you) then you can choose to have none of it shared; it will stay solely
      between you and ditto servers, and your healthcare provide if you so
      choose.
    </p>

    <h3 id="principles-security">Security</h3>
    <p>
      As an incredibly paranoid individual, a former DoD employee, and a
      human being with a brain I take security very seriously.
      All connections are encrypted, all data is encrypted, best-practices
      are employed, industry-standards are followed, code is public and
      auditable, and data never has to leave your device if you don't want it
      to.
    </p>
    <p>
      All connections are encrypted in-transit with TLS at all times to a
      audited, certified servers.
    </p>
    <p>
      Personal data is encrypted on ditto with AES-256 bit encryption and
      SHA-512 salted hashes.
    </p>
    <p>
      Best practices in all applicable technology is adhered to when coding,
      managing servers, and handling data.
    </p>
    <p>
      Healthcare information security standards, such as the HIPAA Security
      Rule, are followed when handling data and servers.
    </p>
    <p>
      All code running ditto is available to the public for transparency,
      contributions, and auditing.
    </p>
    <p>
      If you don't want ditto to ever even see your data, it doesn't need to;
      all data can be kept locally if desired.
    </p>

    <h3 id="principles-privacy">Privacy</h3>
    <p>
      Because your information is massively important, but so is seeking any
      help you can get, you do not even need to deanonymize yourself in any
      way to use ditto. An account is not required to use ditto, and a web
      app is available as a tor hidden service.
    </p>
    <p>
      <b>Disclaimer:</b> if ditto is used without an account, the user runs
      the risk of losing their data rather easily.
    </p>

    <h3 id="principles-helping">Helping</h3>
    <p>
      Ditto is interested exclusively in helping people improve their low
      mood and save lives where possible.
    </p>
    <p>
      Ditto is never going to publish any data that the user does not consent
      to by actually knowing about it and agreeing (not just hidden in the
      privacy policy), and will never release any data to any organization
      (government or otherwise) without obtaining explicit consent from the
      user after informing and discussing with them.
    </p>
    <p>
      Ditto and I don't care about money. Ditto is not made to make money,
      nor further research, nor help organizations; it is made to help the
      patient. If organizations or research can be helped as well, awesome,
      otherwise, awesome.
    </p>
  </div>
</div>

<div class="ribbon">
  <div class="container">
    <h1 id="effectiveness">Effectiveness</h1>

    <p>
      <b>
        Effectiveness of ditto is currently unknown. Once studied, those
        results and results that can be generated by the system will be
        regularly published.
      </b>
    </p>
  </div>
</div>

<div class="ribbon">
  <div class="container">
    <h1 id="accuracy">Accuracy</h1>

    <p>
      <b>
        Accuracy of ditto is currently unknown. Once studied, those results
        and results that can be generated by the system will be regularly
        published.
      </b>
    </p>
  </div>
</div>

<div class="ribbon">
  <div class="container">
    <h1 id="collected">Indexes formed</h1>

    <p>
      <b>
        A variety of indexes created, tested, and verified by professionals
        are utilized in ditto.
      </b>
    </p>

    <p>
      PHQ-9 by Pfizer is used in tracking low mood.
      Logging your mood is a modified PHQ-9 form.
    </p>

    <p>
      HEI-2010 by the USDA is used in tracking eating.
      Logging food is a modified HEI-2010 form.
    </p>
  </div>
</div>

<div class="ribbon">
  <div class="container">
    <h1 id="reported">Data That Is Reported</h1>

    <p>
      <b>
        Ditto reports no data automatically, but there is a variety of data
        contained in reports users can have generated in order to provide
        professionals with.
      </b>
    </p>

    <p>
      Graphs of data, filled-in index forms for viewing and filing, important
      correlations found, with certainty ratings, indexes/forms used, notes on
      modifications, and notes on how correlations are found.
    </p>
  </div>
</div>

<?php
require("../assets/php/footer.php");
?>
