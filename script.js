const mouse = {
    x: undefined,
    y: undefined
};

const sidebarContent = document.getElementById('sidebar-content');


function getParallaxOffset() {
    const maxOffset = 10; // This determines the maximum movement in pixels

    // Calculate the distance from the center for both axes
    const dx = (mouse.x - innerWidth / 2) / (innerWidth / 2);
    const dy = (mouse.y - innerHeight / 2) / (innerHeight / 2);

    return {
        x: dx * maxOffset,
        y: dy * maxOffset
    };
}

class Dot {
    constructor(x, y, dx, dy, radius) {
        this.x = x;
        this.y = y;
        this.dx = dx;
        this.dy = dy;
        this.radius = radius;
        this.opacity = 1; // Fully opaque initially
    }

    fadeOut() {
        if (this.opacity > 0) {
            this.opacity -= 0.1; // Adjust fade out speed here
        }
    }

    isHovered() {
        const distance = Math.sqrt((this.x - mouse.x) ** 2 + (this.y - mouse.y) ** 2);
        return distance < this.radius + 100; 
    }

    // Update dot's position
    update() {
    if (this.x + this.radius > innerWidth || this.x - this.radius < 0) {
        this.dx = -this.dx;
    }
    if (this.y + this.radius > innerHeight || this.y - this.radius < 0) {
        this.dy = -this.dy;
    }

    this.x += this.dx;
    this.y += this.dy;

    // Resizing based on hover
    if (this.isHovered() && this.radius < 4) { // 4 is the max radius on hover
        this.radius += 1;
    } else if (!this.isHovered() && this.radius > 2) { // 2 is the original radius
        this.radius -= 1;
    }
}


    // Draw dot on the canvas
    draw(ctx) {
        const offset = getParallaxOffset();
        ctx.globalAlpha = this.opacity;
        ctx.fill();
        ctx.globalAlpha = 1; // Reset globalAlpha so it doesn't affect
        ctx.beginPath();
        ctx.arc(this.x + offset.x, this.y + offset.y, this.radius, 0, Math.PI * 2, false);
        ctx.fill();
    }
    
}
const canvas = document.getElementById('canvas');
const ctx = canvas.getContext('2d');
canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

// Define a density for the dots (number of dots per pixel squared)
const dotDensity = 0.0001; // Adjust this value based on desired density

// Calculate the total number of dots based on screen size
const totalDots = Math.floor(innerWidth * innerHeight * dotDensity);

let dots = []; 

for (let i = 0; i < totalDots; i++) {
    let radius = 2;
    let x = Math.random() * (innerWidth - 2 * radius) + radius;
    let y = Math.random() * (innerHeight - 2 * radius) + radius;
    let dx = (Math.random() - 0.5) * 2;
    let dy = (Math.random() - 0.5) * 2;
    dots.push(new Dot(x, y, dx, dy, radius));
}

function drawLines(dot1, dot2) {
    const distance = Math.sqrt((dot1.x - dot2.x) ** 2 + (dot1.y - dot2.y) ** 2);
    if (distance < 100) {
        const offset = getParallaxOffset();

        ctx.beginPath();
        ctx.moveTo(dot1.x + offset.x, dot1.y + offset.y);
        ctx.lineTo(dot2.x + offset.x, dot2.y + offset.y);
        ctx.strokeStyle = '#aaa';
        ctx.stroke();
    }
}

function addNewDot() {
    let radius = 2;
    let x = Math.random() * (innerWidth - 2 * radius) + radius;
    let y = Math.random() * (innerHeight - 2 * radius) + radius;
    let dx = (Math.random() - 0.5) * 2;
    let dy = (Math.random() - 0.5) * 2;
    dots.push(new Dot(x, y, dx, dy, radius));
}


function animate() {
    ctx.clearRect(0, 0, innerWidth, innerHeight);

        // Fade out excess dots
    if (dots.length > targetDotsCount) {
        for (let i = targetDotsCount; i < dots.length; i++) {
            dots[i].fadeOut();
        }
    }

    // Render and update dots
    dots = dots.filter(dot => dot.opacity > 0); // Remove fully faded-out dots

    if (dots.length < targetDotsCount) {
        addNewDot();
    }

    for (let dot of dots) {
        dot.update();
        dot.draw(ctx);
    }

    for (let i = 0; i < dots.length; i++) {
        for (let j = i + 1; j < dots.length; j++) {
            drawLines(dots[i], dots[j]);
        }
    }

    requestAnimationFrame(animate);
}

canvas.addEventListener('mousemove', function(event) {
    mouse.x = event.x;
    mouse.y = event.y;
});

const sidebar = document.getElementById('sidebar');
let accumulatedScroll = 0; 

const scrollUpText = document.getElementById('scrollUpText');

if (getComputedStyle(sidebar).display !== 'none') {
    // Show the scroll up text
    scrollUpText.style.display = 'block';
} else {
    // Hide the scroll up text
    scrollUpText.style.display = 'none';
}

function handleScroll(event) {

    const sidebar = document.getElementById('sidebar');
    const scrollUpText = document.getElementById('scrollUpText');

    if (getComputedStyle(sidebar).display !== 'none') {
        // Show the scroll up text
        scrollUpText.style.display = 'block';
    } else {
        // Hide the scroll up text
        scrollUpText.style.display = 'none';
    }

    if (sidebar.style.display === 'none' || getComputedStyle(sidebar).display === 'none') {
        return; // Exit the function if the sidebar is hidden
    }
    // Determine the scroll direction (positive for down, negative for up)
    const scrollDirection = event.deltaY;

    // Update the accumulated scroll value
    accumulatedScroll += scrollDirection;

    // Normalize the accumulated scroll to a value between 0 and 1
    const normalizedScroll = Math.min(Math.max(accumulatedScroll / 10, 0), 1);

    // Adjust the height of the sidebar
    sidebar.style.height = `${normalizedScroll * 100}%`;

    const contentPadding = (1 - normalizedScroll) * 200; 
    sidebarContent.style.paddingBottom = `${contentPadding}px`;

    // Adjust the opacity of the "Scroll Up" text based on scroll direction
    if (scrollDirection < 0) {
        // Scrolling up
        scrollUpText.style.opacity = 1;
    } else {
        // Scrolling down or stationary
        scrollUpText.style.opacity = 0;
    }
}

window.addEventListener('wheel', handleScroll);

// Global variable to keep track of the target number of dots
let targetDotsCount = dots.length;
function handleResize() {
    // Update canvas dimensions
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;

    // Calculate the new target number of dots based on the new dimensions
    const newTargetDotsCount = Math.floor(innerWidth * innerHeight * dotDensity);
    targetDotsCount = Math.max(targetDotsCount, newTargetDotsCount);

    // Reposition dots within the new dimensions and remove fully faded-out dots
    dots = dots.filter(dot => {
        if (dot.opacity > 0) {
            dot.x = Math.min(dot.x, canvas.width - dot.radius);
            dot.y = Math.min(dot.y, canvas.height - dot.radius);
            return true;
        }
        return false;
    });
}

window.addEventListener('resize', handleResize);



// Add the resize event listener
window.addEventListener('resize', handleResize);







animate();
