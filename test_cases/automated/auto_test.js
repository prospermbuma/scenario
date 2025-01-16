/* =================================================
# Automated Test Case 01 - Using Selenium or Jest
==================================================*/

describe("User Registration Form", () => {
    test("should display error for invalid email", () => {
        const email_input = document.querySelector("#email");
        email_input.value = "abc.com";
        const submitBtn = document.querySelector("#submit");
        submitBtn.click();
        const errorMsg = document.querySelector("#email-error");
        expect(errorMsg.textContent).toBe("Invalid email address.");
    });
});