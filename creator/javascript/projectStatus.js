document.addEventListener("DOMContentLoaded", () => {
  const acceptButtons = document.querySelectorAll(".accept-btn");
  const rejectButtons = document.querySelectorAll(".reject-btn");

  acceptButtons.forEach((button) => {
    button.addEventListener("click", () => {
      const projectTitle = button.getAttribute("data-project-title");
      const buyerEmail = button.getAttribute("data-buyer-email");

      if (
        confirm(`Are you sure you want to accept the project: ${projectTitle}?`)
      ) {
        updateProjectStatus(projectTitle, buyerEmail, "accepted");
      }
    });
  });

  rejectButtons.forEach((button) => {
    button.addEventListener("click", () => {
      const projectTitle = button.getAttribute("data-project-title");
      const buyerEmail = button.getAttribute("data-buyer-email");

      if (
        confirm(`Are you sure you want to reject the project: ${projectTitle}?`)
      ) {
        updateProjectStatus(projectTitle, buyerEmail, "rejected");
      }
    });
  });

  function updateProjectStatus(projectTitle, buyerEmail, status) {
    fetch("./updateProjectStatus.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ projectTitle, buyerEmail, status }),
    })
      .then((response) => response.json())
      .then((data) => {
        alert(data.message);
        location.reload(); // Reload the page to reflect changes
      })
      .catch((error) => console.error("Error:", error));
  }
});
