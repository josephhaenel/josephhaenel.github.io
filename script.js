// Constants
const MAX_OFFSET = 10; // Max movement in pixels for parallax
const DOT_DENSITY = 0.0001; // Dots per pixel squared
const MAX_RADIUS_ON_HOVER = 4;
const ORIGINAL_RADIUS = 2;
const FADE_OUT_SPEED = 0.1;
const MAX_DISTANCE_FOR_LINE = 100;
const SCROLL_NORMALIZATION_FACTOR = 10;
const CONTENT_PADDING_FACTOR = 200;

// Global variables
let dots = [];
let targetDotsCount;
let accumulatedScroll = 0;
const mouse = { x: undefined, y: undefined };

// DOM elements
const canvas = document.getElementById('canvas');
const ctx = canvas.getContext('2d');
const sidebar = document.getElementById('sidebar');
const sidebarContent = document.getElementById('sidebar-content');
const scrollUpText = document.getElementById('scrollUpText');

// Initialize canvas size
canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

// Class representing a Dot on the canvas
class Dot {
    constructor(x, y, dx, dy, radius) {
        this.x = x;
        this.y = y;
        this.dx = dx;
        this.dy = dy;
        this.radius = radius;
        this.opacity = 1; // Fully opaque initially
    }

    // Fades out the dot by reducing its opacity
    fadeOut() {
        if (this.opacity > 0) {
            this.opacity -= FADE_OUT_SPEED;
        }
    }

    // Checks if the mouse is hovering over the dot
    isHovered() {
        const distance = Math.sqrt((this.x - mouse.x) ** 2 + (this.y - mouse.y) ** 2);
        return distance < this.radius + MAX_DISTANCE_FOR_LINE; 
    }

    // Updates the dot's position and radius based on interactions
    update() {
        // Bounce off the canvas edges
        if (this.x + this.radius > innerWidth || this.x - this.radius < 0) {
            this.dx = -this.dx;
        }
        if (this.y + this.radius > innerHeight || this.y - this.radius < 0) {
            this.dy = -this.dy;
        }

        // Update position
        this.x += this.dx;
        this.y += this.dy;

        // Resize based on hover state
        if (this.isHovered() && this.radius < MAX_RADIUS_ON_HOVER) {
            this.radius += 1;
        } else if (!this.isHovered() && this.radius > ORIGINAL_RADIUS) {
            this.radius -= 1;
        }
    }

    // Draws the dot on the canvas
    draw(ctx) {
        const offset = getParallaxOffset();
        ctx.globalAlpha = this.opacity;
        ctx.beginPath();
        ctx.arc(this.x + offset.x, this.y + offset.y, this.radius, 0, Math.PI * 2, false);
        ctx.fill();
        ctx.globalAlpha = 1; // Reset globalAlpha
    }
}

// Functions
function getParallaxOffset() {
    const dx = (mouse.x - innerWidth / 2) / (innerWidth / 2);
    const dy = (mouse.y - innerHeight / 2) / (innerHeight / 2);
    return { x: dx * MAX_OFFSET, y: dy * MAX_OFFSET };
}

function drawLines(dot1, dot2) {
    const distance = Math.sqrt((dot1.x - dot2.x) ** 2 + (dot1.y - dot2.y) ** 2);
    if (distance < MAX_DISTANCE_FOR_LINE) {
        const offset = getParallaxOffset();
        ctx.beginPath();
        ctx.moveTo(dot1.x + offset.x, dot1.y + offset.y);
        ctx.lineTo(dot2.x + offset.x, dot2.y + offset.y);
        ctx.strokeStyle = '#aaa';
        ctx.stroke();
    }
}

function addNewDot() {
    let radius = ORIGINAL_RADIUS;
    let x = Math.random() * (innerWidth - 2 * radius) + radius;
    let y = Math.random() * (innerHeight - 2 * radius) + radius;
    let dx = (Math.random() - 0.5) * 2;
    let dy = (Math.random() - 0.5) * 2;
    dots.push(new Dot(x, y, dx, dy, radius));
}

function handleScroll(event) {
    if (getComputedStyle(sidebar).display === 'none') {
        scrollUpText.style.display = 'none';
        return;
    } else {
        scrollUpText.style.display = 'block';
    }

    const scrollDirection = event.deltaY;
    accumulatedScroll += scrollDirection;
    const normalizedScroll = Math.min(Math.max(accumulatedScroll / SCROLL_NORMALIZATION_FACTOR, 0), 1);
    sidebar.style.height = `${normalizedScroll * 100}%`;
    sidebarContent.style.paddingBottom = `${(1 - normalizedScroll) * CONTENT_PADDING_FACTOR}px`;

    // Update scroll up text visibility based on scrolling direction
    scrollUpText.style.opacity = scrollDirection < 0 ? 1 : 0;
}

function updateScrollUpTextVisibility() {
    // Show or hide the scroll-up text based on the sidebar visibility
    if (getComputedStyle(sidebar).display !== 'none') {
        scrollUpText.style.display = 'block';
    } else {
        scrollUpText.style.display = 'none';
    }
}


function handleResize() {
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
    const newTargetDotsCount = Math.floor(innerWidth * innerHeight * DOT_DENSITY);
    targetDotsCount = Math.max(targetDotsCount, newTargetDotsCount);
    dots = dots.filter(dot => dot.opacity > 0 && dot.x < canvas.width && dot.y < canvas.height);
}

function animate() {
    ctx.clearRect(0, 0, innerWidth, innerHeight);

    if (dots.length > targetDotsCount) {
        for (let i = targetDotsCount; i < dots.length; i++) {
            dots[i].fadeOut();
        }
    }

    dots = dots.filter(dot => dot.opacity > 0);
    if (dots.length < targetDotsCount) {
        addNewDot();
    }

    dots.forEach(dot => {
        dot.update();
        dot.draw(ctx);
        dots.forEach(otherDot => {
            if (dot !== otherDot) {
                drawLines(dot, otherDot);
            }
        });
    });

    requestAnimationFrame(animate);
}

// Event Listeners
canvas.addEventListener('mousemove', event => {
    mouse.x = event.x;
    mouse.y = event.y;
});

window.addEventListener('wheel', handleScroll);
window.addEventListener('resize', () => {
    handleResize();
    updateScrollUpTextVisibility();
});

// Initialize
targetDotsCount = Math.floor(innerWidth * innerHeight * DOT_DENSITY);
for (let i = 0; i < targetDotsCount; i++) {
    addNewDot();
}
updateScrollUpTextVisibility();
animate();
