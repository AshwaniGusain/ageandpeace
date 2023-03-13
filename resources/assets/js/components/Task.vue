<template>
    <div class="list-group-item task" :class="[category, {completed}, {loading}]" @click="show">

        <div v-if="category == 'health-and-wellness'" class="mr-2 icon-container" title="Health and Wellness">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 35.07 41.49" class="icon icon-2x"><g data-name="Layer 2"><path d="M17.53 41.49a3 3 0 0 1-1-.18C-.37 35.45-.14 21.79 0 11.81V7.88a2 2 0 0 1 2-2h1c5.26 0 9.07-1.43 12.36-4.64l.67-.67a2.07 2.07 0 0 1 2.83 0l.68.67c3.4 3.19 7.22 4.63 12.46 4.63h1a2 2 0 0 1 2 2v4.01c.17 10 .41 23.58-16.51 29.42a2.9 2.9 0 0 1-1 .18M2.12 7.66a.23.23 0 0 0-.24.24v3.95c-.16 9.45-.39 22.36 15.29 27.79a1.09 1.09 0 0 0 .79 0c15.69-5.42 15.48-17.77 15.31-27.7V7.9a.23.23 0 0 0-.24-.24H32c-5.72 0-9.92-1.59-13.61-5.14l-.69-.69a.18.18 0 0 0-.29 0l-.68.68C13.06 6.08 8.86 7.66 3.09 7.66h-1" fill="#eb7855"></path><path d="M21.28 19.49h-2.65v-2.66a1.1 1.1 0 0 0-2.2 0v2.65h-2.65a1.1 1.1 0 0 0 0 2.2h2.65v2.65a1.1 1.1 0 0 0 2.2 0v-2.65h2.65a1.1 1.1 0 0 0 0-2.2z" fill="#eb7855"></path><path d="M17.56 28.76a8 8 0 1 1 8-8 8 8 0 0 1-8 8m0-24.19A20 20 0 0 1 3.95 9.72v2.15c-.16 9.21-.36 20.67 13.62 25.68 14-5 13.79-16.41 13.63-25.61V9.72a20.13 20.13 0 0 1-13.66-5.14" fill="#f3ae99"></path></g></svg>
        </div>

        <div v-if="category == 'financial-and-insurance'" class="mr-2 icon-container" title="Financial and Insurance">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 39.4 44.24" class="icon icon-2x"><g data-name="Layer 2"><path d="M19.7 24.13a1.15 1.15 0 0 0-1.15 1.15v13.43a3.46 3.46 0 0 1-6.91 0v-.52a1.15 1.15 0 1 0-2.3 0v.52a5.76 5.76 0 0 0 11.51 0V25.28a1.15 1.15 0 0 0-1.15-1.15zM26.25 23a1.84 1.84 0 0 1-1.31-.54c-.7-.68-2.49-1.47-5.21-1.47s-4.47.74-5.21 1.47a1.85 1.85 0 0 1-2.61 0 8.08 8.08 0 0 0-5.21-1.47h-.22a9.78 9.78 0 0 0-4.11.9 1.65 1.65 0 0 1-.76.19A1.62 1.62 0 0 1 0 20.3 19.82 19.82 0 0 1 18.56 2.07h.19V1a1 1 0 1 1 1.9 0v1.06h.19A19.82 19.82 0 0 1 39.4 20.31a1.61 1.61 0 0 1-2.29 1.54 9.78 9.78 0 0 0-4.11-.9h-.24c-2.56 0-4.46.74-5.2 1.47a1.84 1.84 0 0 1-1.3.54M20 19a14.75 14.75 0 0 1 6.09 1.64l.1.06.1-.05A12.82 12.82 0 0 1 32.45 19h.4a12.44 12.44 0 0 1 4.27.76l.31.11v-.33A17.93 17.93 0 0 0 22.62 4.17a20.86 20.86 0 0 0-2.92-.34 18.67 18.67 0 0 0-2.05.22l-.86.12A17.93 17.93 0 0 0 2 19.59v.33l.31-.11A12.43 12.43 0 0 1 6.56 19h.29a15.39 15.39 0 0 1 6.35 1.51l.1.06.1-.05a13.33 13.33 0 0 1 6-1.52h.6" fill="#42a76e"></path><path d="M24.54 6.64a.48.48 0 0 0-.42.72 24.27 24.27 0 0 1 3 10.2.47.47 0 0 0 .47.44h.14a14.3 14.3 0 0 1 4.73-.83s1.1 0 1.83.07a.48.48 0 0 0 .39-.21.51.51 0 0 0 .07-.45 15.84 15.84 0 0 0-10.2-9.93m-4.85-.92h-.56a1.92 1.92 0 0 0-1.38.83A22.91 22.91 0 0 0 14 17.53a.44.44 0 0 0 .46.48h.14a14.65 14.65 0 0 1 4.8-.85h.6a16 16 0 0 1 4.83.93h.12a.45.45 0 0 0 .33-.14.45.45 0 0 0 .12-.34 22.88 22.88 0 0 0-3.74-11 1.92 1.92 0 0 0-1.43-.84h-.49m-4.99.89a16 16 0 0 0-10.07 10 .45.45 0 0 0 .02.34.46.46 0 0 0 .4.19c.6 0 1.61-.07 1.75-.08a19.07 19.07 0 0 1 4.89.78h.11a.47.47 0 0 0 .47-.44 24.32 24.32 0 0 1 3-10.15.48.48 0 0 0 0-.48c-.09-.15-.22-.21-.52-.21" fill="#84c6a1"></path></g></svg>
        </div>

        <div v-if="category == 'legal-and-legacy'" class="mr-2 icon-container" title="Legal and Legacy">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40.05 37.84" class="icon icon-2x"><g data-name="Layer 2"><path d="M36.69 16.32h-8.16a4.1 4.1 0 0 0 8.15 0m-25.1 6.14H3.42a4.1 4.1 0 0 0 8.15 0" fill="#87bbe6"></path><path d="M26.5 34h.34-13.37.26c-2.81 0-5 1.39-5 2.93v.88h22.8V37c0-1.58-2.21-3-5.03-3zM40 15.53C37.33 9.73 34.17 2.74 33 1c-.17-.43-4.86.94-8.61 1.87A4.36 4.36 0 0 0 20 0c-2.56.17-4.18 1.88-4.6 5L7.24 7a1 1 0 0 0-.51.51S0 21.69 0 22.09a7.5 7.5 0 1 0 15 0l-.08-.34L8.69 8.37l7.16-1.79a4.55 4.55 0 0 0 3.41 2.64V30h-2.47a3.34 3.34 0 0 0-3.18 2.86h13.07a3.31 3.31 0 0 0-3.24-2.77H21V9.22c2.47-.6 3.58-2.3 3.83-4.86l6.31-1.45-6 12.61-.08.34a7.5 7.5 0 0 0 15 0zm-27.48 5.13a5.37 5.37 0 0 1 .67 2.19v.06a1.64 1.64 0 0 1-.07.42v.06a5.73 5.73 0 0 1-11.22-.14v-.4a4 4 0 0 1 .33-1.62l.08-.17c.08-.18.15-.31.19-.4l5-10.84zm7.59-13a3 3 0 0 1-3-3 3 3 0 0 1 3-3.07 3 3 0 0 1 3 3 3.09 3.09 0 0 1-3 3.1zm18.07 9.8a5.85 5.85 0 0 1-5.6 4.23 5.71 5.71 0 0 1-5.58-4.4v-.57a4.31 4.31 0 0 1 .75-2.44l4.81-10.69 5.06 10.77c.87 1.57.88 2.14.56 3.13z" fill="#4797d9"></path></g></svg>
        </div>

        <div v-if="category == 'home-and-care'" class="mr-2 icon-container" title="Home and Care">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 39.29 38.48" class="icon icon-2x"><g data-name="Layer 2"><path d="M19.65 4.44L6.88 14.88v20h6.17V23.27a2.72 2.72 0 0 1 2.71-2.72h7.74a2.72 2.72 0 0 1 2.71 2.72v11.58h6.19L32.48 15 19.65 4.44" fill="#f3c783"></path><path d="M39 15.68l-3-2.37V5a.91.91 0 0 0-.91-.91h-3.23a.91.91 0 0 0-.91.92v4.05L20.23.21a.92.92 0 0 0-1.17 0L.33 15.68a.91.91 0 1 0 1.17 1.41l1.76-1.45v21.92a.91.91 0 0 0 .91.91h11.68a.84.84 0 0 0 .84-.84V24.19h5.91v13.45a.84.84 0 0 0 .84.84h11.68a.92.92 0 0 0 .92-.92V15.64l1.76 1.46a.91.91 0 0 0 1.2-1.42zm-4.75 21h-9.82V23.27a.92.92 0 0 0-.92-.92h-7.74a.92.92 0 0 0-.92.92v13.38H5.08L5 14.16 19.65 2.1l14.55 12z" fill="#eca941"></path></g></svg>
        </div>

        <div v-if="category == 'social-and-spiritual'" class="mr-2 icon-container" title="Social and Spiritual">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 41.32 38.37" class="icon icon-2x"><g data-name="Layer 2"><path d="M41 15.92l-.2-.27a10.39 10.39 0 0 0-3.07-3 7.85 7.85 0 0 0-5.09-1 9.69 9.69 0 0 0-4.93 2.66 31.16 31.16 0 0 0-2.32-2.16C21.75 9 18.58 6.13 17 2.2a10.29 10.29 0 0 1-.5-1.63.73.73 0 0 0-.34-.47c-.51-.3-.87.1-1.11.37s-.33.38-.59.69a23.37 23.37 0 0 0-4 7.49 16.26 16.26 0 0 1-3.71-2.84 10.75 10.75 0 0 1-1.07-1.33.72.72 0 0 0-.49-.31c-.58-.09-.77.42-.89.75-.06.18-.17.47-.28.86a22.59 22.59 0 0 0-.72 10.35 15.52 15.52 0 0 0 5.21 9.18c-2.28.36-4.87.66-7.86.91a.71.71 0 0 0-.6 1A17.71 17.71 0 0 0 8 36a20.19 20.19 0 0 0 9.62 2.38h1.07c5.6-.27 10.4-2.63 12.84-6.31a44.66 44.66 0 0 0 3.58-7.22c1.66-3.92 3.23-7.62 5.5-7.62a.72.72 0 0 0 .39-1.31m-7.26 8.33a43.6 43.6 0 0 1-3.46 7c-2.16 3.25-6.65 5.42-11.72 5.67-5.87.29-13.24-2-16.82-9.35 3.17-.29 5.9-.65 8.26-1.07a21.76 21.76 0 0 0 3 1.74.72.72 0 0 0 .61-1.29C8 24.22 4.86 19.82 4.46 13.86a22.15 22.15 0 0 1 .87-7.48l.38.42c3.14 3.3 7.33 4.87 12.09 6.45 3.3 1.09 7.25 2.81 8.51 6.42a.72.72 0 0 0 1.35-.47 8.6 8.6 0 0 0-.87-1.78c1.6-1.9 3.56-3.93 6.12-4.36a6.47 6.47 0 0 1 4.17.85A7.91 7.91 0 0 1 39.32 16c-2.41.9-3.87 4.35-5.53 8.26" fill="#895bb8" fill-rule="evenodd"></path><path d="M33 17.5a.9.9 0 1 1 .9-.9.9.9 0 0 1-.9.9M6.35 9.67a19.05 19.05 0 0 0-.19 4.08C6.51 19 9.29 23 14.42 25.4a2.42 2.42 0 0 1-1 4.6 2.4 2.4 0 0 1-1-.23 24.27 24.27 0 0 1-2.68-1.48c-1.55.26-3.21.49-5.07.7 4.15 5.8 11 6.25 13 6.25h.88C23.06 35 27 33.11 28.91 30.3a42.44 42.44 0 0 0 3.31-6.71c1.4-3.31 2.62-6.18 4.49-7.91a2.82 2.82 0 0 0-.42-.29 5.28 5.28 0 0 0-2.49-.72 3.68 3.68 0 0 0-.59 0c-1.76.29-3.24 1.69-4.34 2.9a10.38 10.38 0 0 1 .41 1 2.42 2.42 0 0 1-4.56 1.6c-1-2.91-4.51-4.4-7.44-5.37-4.13-1.37-7.81-2.7-10.93-5.18" fill="#b294d1"></path></g></svg>
        </div>

        <div v-if="category == 'care-giving-and-tech'" class="mr-2 icon-container" title="Care Giving and Tech">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 41.85 37.2" class="icon icon-2x"><g data-name="Layer 2"><path d="M30.87 21.7a3.6 3.6 0 0 0 0 7.2 3.6 3.6 0 0 0 0-7.2" fill="#e28fb1"></path><path d="M8.07 14.76a.83.83 0 0 1-.75-.47 5.31 5.31 0 0 1 1.34-5.94 5.67 5.67 0 0 1 4-1.76 5.49 5.49 0 0 1 2.35.54.84.84 0 1 1-.71 1.51 3.84 3.84 0 0 0-1.64-.38 4 4 0 0 0-2.81 1.26 3.7 3.7 0 0 0-1 4 .83.83 0 0 1-.39 1.12.82.82 0 0 1-.36.08M29.54 4a8.17 8.17 0 0 0-5.81 2.43L21 9.18l-2.78-2.75a8.48 8.48 0 0 0-6-2.42 8.78 8.78 0 0 0-1.09.07 8.47 8.47 0 0 0-3.86 1.64 8.32 8.32 0 0 0-.83 12.48l14.35 14.35.82-.8a2.43 2.43 0 0 1 0-2.32l.26-.47-.52-.15a2.44 2.44 0 0 1-1.74-2.36V24a2.43 2.43 0 0 1 1.75-2.31l.55-.15-.26-.47a2.41 2.41 0 0 1 .42-2.9l1.75-1.73a2.45 2.45 0 0 1 1.7-.69 2.42 2.42 0 0 1 1.2.32l.47.26.16-.53a2.4 2.4 0 0 1 2.32-1.74h2.47a2.42 2.42 0 0 1 2.36 1.84l.13.48.48-.26a2.42 2.42 0 0 1 1.16-.29 2.49 2.49 0 0 1 .77.14 8.42 8.42 0 0 0 .88-3.4A8.4 8.4 0 0 0 29.54 4" fill="#e28fb1"></path><path d="M39.94 23.27l-1.7-.49a7.79 7.79 0 0 0-.34-.78l.87-1.53v-.07a12.45 12.45 0 0 0 3.05-7.7A12.1 12.1 0 0 0 37 2.51 11.83 11.83 0 0 0 31.21.12 13.22 13.22 0 0 0 29.59 0a12.41 12.41 0 0 0-8.22 3.09l-.45.39-.44-.4A12.16 12.16 0 0 0 12.33 0a12.65 12.65 0 0 0-1.73.12A12.24 12.24 0 0 0 0 12.31 12.07 12.07 0 0 0 3.63 21L19.1 36.48a2.33 2.33 0 0 0 3.36 0l3.39-3.32 1.56-.86a7.69 7.69 0 0 0 .8.33l.48 1.72a.89.89 0 0 0 .85.64L32 35a.92.92 0 0 0 .85-.64l.5-1.71a7.72 7.72 0 0 0 .8-.32l1.55.88a.86.86 0 0 0 .42.11.9.9 0 0 0 .64-.26l1.75-1.73a.89.89 0 0 0 .15-1.05l-.84-1.55a8 8 0 0 0 .34-.81l1.71-.47a.89.89 0 0 0 .64-.85v-2.46a.9.9 0 0 0-.57-.87zM20.77 35.36L5 19.61A10.33 10.33 0 0 1 6 4.13a10.45 10.45 0 0 1 4.86-2 10.85 10.85 0 0 1 1.4-.13 10.47 10.47 0 0 1 7.36 3L21 6.35 22.31 5a10.16 10.16 0 0 1 7.22-3 10.41 10.41 0 0 1 1.45.1 10.3 10.3 0 0 1 9 10.53 10.6 10.6 0 0 1-2.12 5.91l-.91-.92a.9.9 0 0 0-1.05-.15l-1.56.85a7.87 7.87 0 0 0-.8-.34L33 16.27a.9.9 0 0 0-.86-.67h-2.46a.9.9 0 0 0-.86.65l-.5 1.7a7.76 7.76 0 0 0-.8.33L26 17.39a.88.88 0 0 0-.43-.11.89.89 0 0 0-.63.25l-1.75 1.73a.9.9 0 0 0-.19 1.06l.85 1.55a7.88 7.88 0 0 0-.35.81l-1.71.47a.89.89 0 0 0-.64.85v2.46a.9.9 0 0 0 .64.87l1.7.49a7.89 7.89 0 0 0 .33.81L23 30.18a.89.89 0 0 0 .14 1.06l.94.95zm18-9.45l-1.5.42a.9.9 0 0 0-.61.61 5.93 5.93 0 0 1-.58 1.37.89.89 0 0 0 0 .86l.75 1.37-.8.79-1.36-.76a.89.89 0 0 0-.87 0 6.1 6.1 0 0 1-1.39.56.9.9 0 0 0-.61.61l-.43 1.48h-1.12l-.41-1.49a.9.9 0 0 0-.61-.61 6 6 0 0 1-1.38-.58.9.9 0 0 0-.43-.11.88.88 0 0 0-.43.11l-1.37.75-.79-.8.77-1.36a.89.89 0 0 0 0-.86 6 6 0 0 1-.6-1.4.89.89 0 0 0-.61-.61l-1.49-.44v-1.14l1.5-.42a.9.9 0 0 0 .61-.61 6 6 0 0 1 .58-1.36.89.89 0 0 0 0-.88L24.91 20l.79-.78 1.36.76a.89.89 0 0 0 .87 0 6 6 0 0 1 1.38-.56.9.9 0 0 0 .62-.61l.44-1.5h1.12l.42 1.5a.9.9 0 0 0 .61.61 5.93 5.93 0 0 1 1.38.58.9.9 0 0 0 .43.11.87.87 0 0 0 .43-.11l1.37-.75.78.79-.76 1.35a.9.9 0 0 0-.09.67l.09.21a6 6 0 0 1 .57 1.37.9.9 0 0 0 .61.62l1.49.44z" fill="#d35387"></path></g></svg>
        </div>

        <div>
            <button class="btn btn-checklist-checkbox btn-sm mr-2"
                    v-tooltip.top.end="{content: completeButtonTooltip, offset: 5}"
                    :class="completed ? 'btn-primary' : 'btn-outline-primary'"
                    :disabled="loading" @click.stop="complete">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 17" class="icon">
                    <path d="M892.981835,41 C892.735706,41 892.506687,40.8829061 892.337373,40.6702836 L886.640943,33.010704 C886.498719,32.8203596 886.440262,32.5767259 886.48054,32.3427164 C886.520463,32.1117366 886.649854,31.9130157 886.845011,31.7830896 C886.968699,31.6886303 887.147637,31.6308854 887.326753,31.6308854 C887.594269,31.6308854 887.851448,31.7606332 888.014524,31.9777114 L892.949755,38.6240814 L901.872772,24.4052838 C902.005906,24.1625413 902.293383,24 902.599752,24 C902.762115,24 902.91931,24.0445563 903.054404,24.129035 C903.234055,24.2274152 903.375209,24.4147298 903.432776,24.638224 C903.492303,24.8670651 903.455589,25.112481 903.332079,25.3110237 L893.726994,40.6292918 L893.7204,40.6374902 C893.53968,40.8640143 893.313156,40.9853855 893.058828,40.9959008 C893.029778,40.9994653 892.996806,41 892.981835,41"
                          transform="translate(-886 -24)"/>
                </svg>
            </button>
        </div>
        <div class="task-description mr-2">
            <p class="mb-0">
                {{ title }}
            </p>
            <em class="small text-gray-md" v-if="completed">You completed this task on
                {{dateFormat(completed_date)}}</em>
        </div>

<!--        <div class="btn-toolbar task-toolbar d-none d-sm-flex">-->

        <div class="text-nowrap task-actions">
            <span v-if="due_date" class="xsmall mr-1 align-self-center text-nowrap">Due Date: <strong>{{ dateFormat(due_date) }}</strong></span>
            <button href="#"
                style="min-height: 40px;"
                class="btn btn-sm btn-outline-secondary mr-1"
                :disabled="loading"
                v-text="due_date ? 'Details' : 'Need Help?'"></button>
        </div>


<!--        </div>-->

        <portal to="task-details">
            <div v-if="open" class="overlay" @click="hide"></div>

            <transition name="task">
                <div class="task-details" v-if="open" ref="details">
                    <fetcher :url="'/related-details/' + category_id">
                        <div slot-scope="{fetched:details, loading}">
                            <div class="clearfix bg-white">
                                <button type="button" class="close" @click="hide">
                                    Close
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="icon icon-close">
                                        <polygon
                                                points="82.484 2.121 80.748 .385 70.869 10.264 60.99 .385 59.254 2.121 69.133 12 59.254 21.879 60.99 23.615 70.869 13.736 80.748 23.615 82.484 21.879 72.605 12"
                                                transform="translate(-59)"/>
                                    </svg>
                                </button>
                            </div>

                            <div class="task-details-content bg-white">
                                <div>
                                    <em class="small text-gray-md" v-if="completed">You completed this task on
                                        {{dateFormat(completed_date)}}</em>
                                    <h2 class="mb-4">{{ title }}</h2>
                                    <div class="btn-toolbar btn-toolbar-sm task-toolbar mb-5">

                                        <button class="btn btn-checklist-checkbox btn-sm btn-outline-primary mr-1"
                                                v-tooltip.top="{content: completeButtonTooltip, offset: 5}"
                                                :disabled="loading" @click.stop="complete">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 17" class="icon">
                                                <path d="M892.981835,41 C892.735706,41 892.506687,40.8829061 892.337373,40.6702836 L886.640943,33.010704 C886.498719,32.8203596 886.440262,32.5767259 886.48054,32.3427164 C886.520463,32.1117366 886.649854,31.9130157 886.845011,31.7830896 C886.968699,31.6886303 887.147637,31.6308854 887.326753,31.6308854 C887.594269,31.6308854 887.851448,31.7606332 888.014524,31.9777114 L892.949755,38.6240814 L901.872772,24.4052838 C902.005906,24.1625413 902.293383,24 902.599752,24 C902.762115,24 902.91931,24.0445563 903.054404,24.129035 C903.234055,24.2274152 903.375209,24.4147298 903.432776,24.638224 C903.492303,24.8670651 903.455589,25.112481 903.332079,25.3110237 L893.726994,40.6292918 L893.7204,40.6374902 C893.53968,40.8640143 893.313156,40.9853855 893.058828,40.9959008 C893.029778,40.9994653 892.996806,41 892.981835,41"
                                                      transform="translate(-886 -24)"/>
                                            </svg>
                                        </button>

                                        <date-button v-model="date" @input="setDueDate()"></date-button>
                                        <button class="btn btn-sm btn-outline-secondary mr-1" v-if="date" @click="unsetDueDate()">Unset Date</button>

                                        <a :href="'/dashboard/download/' + file[0].id"
                                           v-if="file.length"
                                           target="_blank"
                                           class="d-flex flex-column justify-content-center btn btn-sm btn-outline-info">
                                            <span class="d-none d-sm-block">Download Files</span>
                                            <span class="d-block d-sm-none">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                 viewBox="0 0 45 54.5" class="icon"><g
                                                                    fill-rule="evenodd"><path
                                                                    d="M5.934 22.818c-1.06-1.28-.576-2.318 1.085-2.318h30.518c1.659 0 2.148 1.034 1.084 2.318l-14.423 17.41c-1.06 1.28-2.777 1.283-3.84 0L5.934 22.818z"/><rect
                                                                    transform="rotate(180 22.5 50.5)" y="47" width="45"
                                                                    height="7" rx="3.5"/><rect
                                                                    transform="rotate(-90 22.5 14.5)" x="8" y="9"
                                                                    width="29" height="11"
                                                                    rx="5.5"/></g></svg>

                                                            </span>
                                        </a>
                                    </div>


                                    <expand-text :content="description" :length="150" v-if="description.length > 150">
                                        <div slot-scope="{expanded, toggle, text}">
                                            <div v-html="text"></div>

                                            <a href="#" @click.prevent="toggle" class="btn btn-sm btn-link p-0">
                                                <span v-if="!expanded">View all</span>
                                                <span v-else>View Less</span>
                                            </a>
                                        </div>
                                    </expand-text>

                                    <div v-else>
                                        {{description}}
                                    </div>
                                </div>
                            </div>

                            <related-providers :providers="details.providers"></related-providers>

                            <div v-if="!loading" class="task-details-content bg-light">
                                <div v-if="details.providerTypes.length" class="mb-6">
                                    <h4 class="mb-3">View Tasks By a Provider Type</h4>

                                    <ul class="list-unstyled">
                                        <li class="mb-2" v-for="providerType in details.providerTypes">
                                            <a :href="'/dashboard/all-tasks?type=' + providerType.slug">{{providerType.name}}</a>
                                        </li>
                                    </ul>
                                </div>

                                <div v-if="details.articles.length">
                                    <h4 class="mb-3">Related Articles</h4>
                                    <ul class="list-unstyled">
                                        <li class="mb-2" v-for="article in details.articles">
                                            <a :href="'/news/' + article.slug">{{article.title}}</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </fetcher>
                </div>
            </transition>
        </portal>
    </div>
</template>

<script>
  import dateButton from './DateButton.vue';
  import relatedProviders from './RelatedProviders.vue';
  import expandText from './ExpandText.vue';
  import fetcher from './Fetcher.vue';

  export default {
    name: 'task',

    components: {
      dateButton,
      relatedProviders,
      fetcher,
      expandText,
    },

    //we'll get these props from the laravel model being passed into the component
    props: ['id', 'category', 'task', 'completed', 'due_date', 'completed_date', 'category_id', 'description', 'title', 'file'],

    data() {
      return {
        open: false,
        loading: false,
        date: this.due_date,
      }
    },

    computed: {
      completeButtonTooltip() {
        return this.completed ? 'Reopen task' : 'Mark this task completed.';
      }
    },

    methods: {
      complete() {
        if (this.loading) {
          return;
        }

        //close first to prevent next task window from opening automatically
        this.open = false;
        this.loading = true;

        axios.post(`/dashboard/update-task-status/${this.id}`, {
          completed: !this.completed,
        }).then(response => {
          this.$emit('task-completed', {
            id: this.id,
            completed: response.data,
          });

          this.loading = false;
        }).catch(error => {
          alert('We\'ve encountered an error. Please try again later.');

          this.loading = false;
        });
      },

      setDueDate() {
        if (this.loading) {
          return;
        }

        this.loading = true;

        axios.post(`/dashboard/update-task-due-date/${this.id}`, {
          due_date: this.date,
        })
        .then(response => {
          this.$emit('task-due-date', {
            id: this.id,
            due_date: response.data,
          });

          this.loading = false;
        })
        .catch(error => {
          alert('We\'ve encountered an error. Please try again later.');

          this.loading = false;
        })
      },

      unsetDueDate() {
          this.date = null;
          this.setDueDate();
      },

      show() {
        this.open = true;
        document.body.addEventListener('keyup', this.onEscape);
      },

      onEscape(e) {
        if (e.keyCode === 27) {
          this.hide();
        }
      },

      hide() {
        this.open = false;
        document.body.removeEventListener('keyup', this.onEscape);
      },
    },
  };
</script>
