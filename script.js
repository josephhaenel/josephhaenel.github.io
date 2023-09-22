window.onload = function() {
    const date = new Date();
    const dateString = date.getFullYear();
    document.getElementById('currentDate').innerText = dateString;
    document.getElementById('myAge').innerText = calculateAge();
};

function calculateAge() {
    const birthDate = new Date(2003, 2, 27);
    const currentDate = new Date();
    let age= currentDate.getFullYear() - birthDate.getFullYear();
    const hasBirthdayPassed = (currentDate.getMonth() > birthDate.getMonth()) || (currentDate.getMonth() === birthDate.getMonth() && currentDate.getDate() >= birthDate.getDate());
    if(!hasBirthdayPassed) {
        age -= 1;
    }
    return age;
}