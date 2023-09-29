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
    
        ctx.beginPath();
        ctx.arc(this.x + offset.x, this.y + offset.y, this.radius, 0, Math.PI * 2, false);
        ctx.fill();
    }
    
}
const canvas = document.getElementById('canvas');
const ctx = canvas.getContext('2d');
canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

const dots = [];

for (let i = 0; i < 100; i++) {
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


function animate() {
    ctx.clearRect(0, 0, innerWidth, innerHeight);

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

function handleScroll(event) {
    // Determine the scroll direction (positive for down, negative for up)
    const scrollDirection = event.deltaY;

    // Update the accumulated scroll value
    accumulatedScroll += scrollDirection;

    // Normalize the accumulated scroll to a value between 0 and 1
    const normalizedScroll = Math.min(Math.max(accumulatedScroll / 10, 0), 1); // 100 makes for a single scroll to pul it up

    // Adjust the height of the sidebar
    sidebar.style.height = `${normalizedScroll * 100}%`;

    const contentPadding = (1 - normalizedScroll) * 200; // 200 is an arbitrary value; adjust as needed
    sidebarContent.style.paddingBottom = `${contentPadding}px`;
}

window.addEventListener('wheel', handleScroll);



animate();
