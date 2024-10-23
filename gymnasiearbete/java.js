// Rainbow text animation när sidan laddas
document.addEventListener('DOMContentLoaded', function () {
    const textElement = document.querySelector('.rainbow-text');
    if (textElement) { 
        const text = textElement.textContent;
        textElement.innerHTML = '';

        [...text].forEach((letter, index) => {
            const span = document.createElement('span');
            span.textContent = letter;
            span.style.setProperty('--i', index);
            textElement.appendChild(span);
        });
    }
});

// Funktion för att toggla hamburgarmenyn och visa rubriken
function toggleMenu(button) {
    // Toggla hamburgarens animering
    button.classList.toggle("change");
  
    // Rubriken och dropdown-menyn
    const menuTitle = document.querySelector('.menu-title');
    const dropdownMenu = document.querySelector('.dropdown-menu');
  
    // Kolla om menyn redan är öppen
    const isOpen = menuTitle.style.display === "block";
  
    if (!isOpen) {
      // Återställ dropdown-menyns startvärden innan den visas
      dropdownMenu.style.opacity = 0;
      dropdownMenu.style.transform = "scale(0)"; // Sätt tillbaka till scale(0) innan öppning

      // Visa rubriken och glid in den
      menuTitle.style.display = "block"; // Visa rubriken
      setTimeout(() => {
        menuTitle.style.transform = "translateX(0) scaleX(1)"; // Glid in rubriken, normal bredd
      }, 50); // Fördröjning för animationen
  
      // Visa dropdown-menyn med fördröjning för en mjukare effekt
      setTimeout(() => {
        dropdownMenu.style.display = "block"; // Visa dropdown-menyn
        setTimeout(() => {
          dropdownMenu.style.transform = "scale(1)"; // Skala upp dropdown-menyn från 0 till 1
          dropdownMenu.style.opacity = 1; // Gör dropdown-menyn synlig
        }, 10); // Kort fördröjning så CSS-ändringar hinner appliceras
      }, 50); // Fördröjning innan dropdown visas
  
      // Animerar dropdown-items
      const dropdownItems = dropdownMenu.querySelectorAll('.dropdown-item');
      dropdownItems.forEach((item, index) => {
        setTimeout(() => {
          item.style.opacity = 1; // Gör item synliga
          item.style.transform = 'scale(1)'; // Skala tillbaka till normalstorlek
          item.style.transitionDelay = `${index * 60}ms`; // Fördröjning för varje item
        }, 500); // Extra fördröjning för att matcha dropdownens uppskalning
      });
    } else {
      // Animering av rubriken
      menuTitle.style.transform = "translateX(-100%) scaleX(0)"; // Glid ut och skala ihop rubriken
  
      // Skala ner dropdown-menyn
      dropdownMenu.style.transform = "scale(0)"; // Skala ner dropdown-menyn till 0
      dropdownMenu.style.opacity = 0; // Gör dropdown-menyn osynlig
  
      // Döljer rubriken och dropdown-menyn efter animationen
      setTimeout(() => {
        menuTitle.style.display = "none"; // Döljer rubriken
        dropdownMenu.style.display = "none"; // Döljer dropdown-menyn
      }, 500); // Samma tid som transition-duration för att matcha animationen
    }
}

alert('Anthony');