document.addEventListener("DOMContentLoaded", function () {
  const cookieConsentBox = document.getElementById("cookieConsent");
  const acceptCookiesButton = document.getElementById("acceptCookies");
  const denyCookiesButton = document.getElementById("denyCookies");

  // Function to check if cookies have been accepted or denied
  const checkCookieConsent = () => {
    const cookiesAccepted = localStorage.getItem("cookiesAccepted");
    if (cookiesAccepted === "true") {
      cookieConsentBox.style.display = "none";
    } else {
      cookieConsentBox.style.display = "flex";
    }
  };

  // Function to accept cookies
  const acceptCookies = () => {
    // Store consent in local storage
    localStorage.setItem("cookiesAccepted", "true");
    // Set a tracking cookie to identify acceptance
    document.cookie = "userConsent=true; path=/; max-age=" + 60 * 60 * 24 * 365; // Expires in 1 year
    cookieConsentBox.style.display = "none";
  };

  // Function to deny cookies
  const denyCookies = () => {
    // Store consent in local storage
    localStorage.setItem("cookiesAccepted", "true");
    // Set a tracking cookie to identify acceptance
    document.cookie = "userConsent=true; path=/; max-age=" + 60 * 60 * 24 * 365; // Expires in 1 year
    cookieConsentBox.style.display = "none";
  };

  // Event listeners for the buttons
  acceptCookiesButton.addEventListener("click", acceptCookies);
  denyCookiesButton.addEventListener("click", denyCookies);

  // Check if consent is already given
  checkCookieConsent();
});
