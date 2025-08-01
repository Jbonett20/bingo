 document.querySelectorAll('.navbar-nav .nav-link').forEach(link => {
    link.addEventListener('click', () => {
      const navbar = document.querySelector('.navbar-collapse');
      const bsCollapse = new bootstrap.Collapse(navbar, {
        toggle: false
      });
      bsCollapse.hide();
    });
  });