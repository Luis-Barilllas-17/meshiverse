document.documentElement.classList.add('js');

const menuButton = document.querySelector('[data-menu-toggle]');
const mobileMenu = document.querySelector('[data-mobile-menu]');

menuButton?.addEventListener('click', () => {
    const isOpen = menuButton.getAttribute('aria-expanded') === 'true';

    menuButton.setAttribute('aria-expanded', String(!isOpen));
    mobileMenu?.classList.toggle('hidden', isOpen);
});

mobileMenu?.querySelectorAll('a').forEach((link) => {
    link.addEventListener('click', () => {
        mobileMenu.classList.add('hidden');
        menuButton?.setAttribute('aria-expanded', 'false');
    });
});

const revealObserver = new IntersectionObserver((entries, observer) => {
    entries.forEach((entry) => {
        if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
            observer.unobserve(entry.target);
        }
    });
}, { threshold: 0.08, rootMargin: '0px 0px -40px' });

document.querySelectorAll('[data-reveal]').forEach((element) => revealObserver.observe(element));

document.querySelectorAll('[data-copy-target]').forEach((button) => {
    button.addEventListener('click', async () => {
        const target = document.getElementById(button.dataset.copyTarget);

        if (!target) {
            return;
        }

        await navigator.clipboard.writeText(target.textContent.trim());
        const originalText = button.textContent;

        button.textContent = 'Copiado ✓';
        window.setTimeout(() => {
            button.textContent = originalText;
        }, 1600);
    });
});

document.querySelectorAll('[data-hama-board]').forEach((canvas) => {
    const patternElement = document.getElementById(canvas.dataset.patternId);
    const context = canvas.getContext('2d');

    if (!patternElement || !context) {
        return;
    }

    let pattern = [];

    try {
        pattern = JSON.parse(patternElement.textContent);
    } catch {
        return;
    }

    const boardWidth = Math.max(1, Number.parseInt(canvas.dataset.boardWidth, 10) || 1);
    const boardHeight = Math.max(1, Number.parseInt(canvas.dataset.boardHeight, 10) || 1);
    const cellSize = Math.min(canvas.width / boardWidth, canvas.height / boardHeight);
    const drawingWidth = cellSize * boardWidth;
    const drawingHeight = cellSize * boardHeight;
    const offsetX = (canvas.width - drawingWidth) / 2;
    const offsetY = (canvas.height - drawingHeight) / 2;
    const numericPositions = pattern
        .map((point) => Number.parseInt(point.posicion, 10))
        .filter(Number.isFinite);
    const usesOneBasedPositions = numericPositions.length > 0 && !numericPositions.includes(0);

    context.fillStyle = '#fffaf0';
    context.fillRect(0, 0, canvas.width, canvas.height);

    pattern.forEach((point) => {
        const rawPosition = Number.parseInt(point.posicion, 10);

        if (!Number.isFinite(rawPosition)) {
            return;
        }

        const position = usesOneBasedPositions ? rawPosition - 1 : rawPosition;
        const column = position % boardWidth;
        const row = Math.floor(position / boardWidth);

        if (position < 0 || column >= boardWidth || row >= boardHeight) {
            return;
        }

        context.fillStyle = point.color || canvas.dataset.mainColor || '#6b3f2a';
        context.beginPath();
        context.arc(
            offsetX + (column * cellSize) + (cellSize / 2),
            offsetY + (row * cellSize) + (cellSize / 2),
            Math.max(1.5, cellSize * 0.39),
            0,
            Math.PI * 2,
        );
        context.fill();
    });

    context.strokeStyle = 'rgba(75, 16, 24, 0.12)';
    context.lineWidth = 1;

    for (let column = 0; column <= boardWidth; column += 1) {
        context.beginPath();
        context.moveTo(offsetX + (column * cellSize), offsetY);
        context.lineTo(offsetX + (column * cellSize), offsetY + drawingHeight);
        context.stroke();
    }

    for (let row = 0; row <= boardHeight; row += 1) {
        context.beginPath();
        context.moveTo(offsetX, offsetY + (row * cellSize));
        context.lineTo(offsetX + drawingWidth, offsetY + (row * cellSize));
        context.stroke();
    }
});
