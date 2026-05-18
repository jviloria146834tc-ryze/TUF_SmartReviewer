<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
    <!-- Left Half: Brain -->
    <g clip-path="url(#left-half)">
        <path d="M12 18V5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        <path d="M15 13a4.17 4.17 0 0 1-3-4 4.17 4.17 0 0 1-3 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        <path d="M17.598 6.5A3 3 0 1 0 12 5a3 3 0 1 0-5.598 1.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        <path d="M17.997 5.125a4 4 0 0 1 2.526 5.77" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        <path d="M18 18a4 4 0 0 0 2-7.464" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        <path d="M19.967 17.483A4 4 0 1 1 12 18a4 4 0 1 1-7.967-.517" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        <path d="M6 18a4 4 0 0 1-2-7.464" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        <path d="M6.003 5.125a4 4 0 0 0-2.526 5.77" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
    </g>

    <!-- Right Half: Book -->
    <g clip-path="url(#right-half)" transform="translate(0, 12) scale(1, 1.4) translate(0, -12)">
        <path d="M12 6.042c1.008-.93 2.488-1.542 4.5-1.542 2.012 0 3.492.612 4.5 1.542V18.5c-1.008-.93-2.488-1.542-4.5-1.542-2.012 0-3.492.612-4.5 1.542m0-12.458c-1.008-.93-2.488-1.542-4.5-1.542-2.012 0-3.492.612-4.5 1.542V18.5c1.008-.93 2.488-1.542 4.5-1.542 2.012 0 3.492.612 4.5 1.253m0-12.458V17.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" vector-effect="non-scaling-stroke" />
        
        <!-- Lines of writing -->
        <path d="M14.5 8c1-0.4 2-0.4 4 0" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" vector-effect="non-scaling-stroke" />
        <path d="M14.5 11c1-0.4 2-0.4 4 0" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" vector-effect="non-scaling-stroke" />
        <path d="M14.5 14c1-0.4 2-0.4 4 0" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" vector-effect="non-scaling-stroke" />
    </g>

    <!-- Center Line (Common) -->
    <path d="M12 2V22" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />

    <defs>
        <clipPath id="left-half">
            <rect x="0" y="0" width="12" height="24" />
        </clipPath>
        <clipPath id="right-half">
            <rect x="12" y="0" width="12" height="24" />
        </clipPath>
    </defs>
</svg>
