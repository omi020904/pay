function validateForm() {
    let errorMessage = "";
    const namePattern = /^[A-Za-z]+$/; // Only alphabets, no spaces
    const collegePattern = /^[A-Za-z\s.]+$/; // Alphabets, dots, and spaces
    const mobilePattern = /^\d{10}$/; // Exactly 10 digits

    // Validate First Name
    const firstName = document.getElementById("first-name").value;
    if (!namePattern.test(firstName)) {
        errorMessage += "First name must only contain alphabets.<br>";
    }

    // Validate Middle Name
    const middleName = document.getElementById("middle-name").value;
    if (middleName && !namePattern.test(middleName)) {
        errorMessage += "Middle name must only contain alphabets.<br>";
    }

    // Validate Last Name
    const lastName = document.getElementById("last-name").value;
    if (!namePattern.test(lastName)) {
        errorMessage += "Last name must only contain alphabets.<br>";
    }

    // Validate College Name
    const collegeName = document.getElementById("collegename").value;
    if (!collegePattern.test(collegeName)) {
        errorMessage += "College name must only contain alphabets, dots, and spaces.<br>";
    }

    // Validate Mobile Number
    const mobileNumber = document.getElementById("mobileno").value;
    if (!mobilePattern.test(mobileNumber)) {
        errorMessage += "Mobile number must be exactly 10 digits.<br>";
    }

    // Display error message if any
    if (errorMessage) {
        document.getElementById("error-message").innerHTML = errorMessage;
        return false; // Prevent form submission
    }

    return true; // Allow form submission
}

function redirectToPayment() {
    if (!validateForm()) return;

    const amount = 1.00; // Replace with the actual amount
    const upiID = document.getElementById("upi-id").value;
    const firstName = document.getElementById("first-name").value;
    const lastName = document.getElementById("last-name").value;
    const name = `${firstName} ${lastName}`;
    const paymentLink = `upi://pay?pa=${upiID}&pn=${name}&tn=Registration Payment&am=${amount}&cu=INR`;

    console.log('Payment Link:', paymentLink); // Debugging line

    // Open UPI payment link
    window.open(paymentLink, '_self');

    // Set a timeout to simulate checking payment status (adjust time as needed)
    setTimeout(function() {
        // Simulate payment success for demonstration purposes
        // In a real-world application, you would check payment status server-side
        document.getElementById("submit-button").style.display = 'block';
    }, 5000); // Adjust timeout as necessary for your needs
}
