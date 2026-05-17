(function () {
  var root = document.documentElement;
  var btn = document.getElementById('themeToggle');

  function systemDark() {
    return window.matchMedia('(prefers-color-scheme: dark)').matches;
  }

  var saved = localStorage.getItem('theme-mode');
  var current = saved || (systemDark() ? 'dark' : 'light');

  function apply(mode) {
    root.setAttribute('data-theme', mode);
    if (btn) {
      btn.textContent = 'тема: ' + (mode === 'dark' ? 'темна' : 'світла');
    }
  }
  apply(current);

  if (btn) {
    btn.addEventListener('click', function () {
      current = current === 'dark' ? 'light' : 'dark';
      localStorage.setItem('theme-mode', current);
      apply(current);
    });
  }

  // Artifact copy button
  var copyBtn = document.querySelector('.artifact-copy');
  if (copyBtn) {
    copyBtn.addEventListener('click', function () {
      var code = document.querySelector('.artifact-body code');
      var text = code ? code.innerText : '';
      function done() {
        copyBtn.textContent = 'скопійовано';
        copyBtn.classList.add('copied');
        setTimeout(function () {
          copyBtn.textContent = 'копіювати';
          copyBtn.classList.remove('copied');
        }, 1800);
      }
      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).then(done).catch(done);
      } else {
        done();
      }
    });
  }
})();
