@extends('layouts.vendor.app')

@section('section')
    <section class="main-content-area">
        <header class="calendar-header">
            <div class="header-controls">
                <button class="btn btn-icon" id="prevMonth">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <h1 class="current-month" id="currentMonth">December 2024</h1>
                <button class="btn btn-icon" id="nextMonth">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>

            <div class="view-controls">
                <button class="btn btn-view active" data-view="month">Month</button>
                <button class="btn btn-view" data-view="week">Week</button>
                <button class="btn btn-view" data-view="day">Day</button>
            </div>
        </header>

        <!-- Calendar Grid -->
        <div class="calendar-container">
            <!-- Month View -->
            <div class="calendar-view month-view active" id="monthView">
                <div class="calendar-weekdays">
                    <div class="weekday">Sun</div>
                    <div class="weekday">Mon</div>
                    <div class="weekday">Tue</div>
                    <div class="weekday">Wed</div>
                    <div class="weekday">Thu</div>
                    <div class="weekday">Fri</div>
                    <div class="weekday">Sat</div>
                </div>
                <div class="calendar-grid" id="monthGrid">
                    <!-- Days will be populated by JavaScript -->
                </div>
            </div>

            <!-- Week View -->
            <div class="calendar-view week-view" id="weekView">
                <div class="week-header">
                    <div class="time-column">Time</div>
                    <div class="day-column" data-day="0">Sun</div>
                    <div class="day-column" data-day="1">Mon</div>
                    <div class="day-column" data-day="2">Tue</div>
                    <div class="day-column" data-day="3">Wed</div>
                    <div class="day-column" data-day="4">Thu</div>
                    <div class="day-column" data-day="5">Fri</div>
                    <div class="day-column" data-day="6">Sat</div>
                </div>
                <div class="week-grid" id="weekGrid">
                    <!-- Time slots will be populated by JavaScript -->
                </div>
            </div>

            <!-- Day View -->
            <div class="calendar-view day-view" id="dayView">
                <div class="day-timeline" id="dayTimeline">
                    <!-- Time slots will be populated by JavaScript -->
                </div>
            </div>
        </div>
        <div class="event-panel d-none" id="eventPanel">
            <div class="panel-header">
                <h3>Events</h3>
                <button class="btn btn-primary" id="addEventBtn">
                    <i class="fas fa-plus"></i> Add Event
                </button>
            </div>
            <div class="events-list" id="eventsList">
            </div>
        </div>

        <!-- Event Modal -->
        <div class="modal d-none" id="eventModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 id="modalTitle">Add New Event</h3>
                    <button class="modal-close" id="closeModal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form id="eventForm">
                    <div class="form-group">
                        <label for="eventTitle">Event Title</label>
                        <input type="text" id="eventTitle" required>
                    </div>
                    <div class="form-group">
                        <label for="eventDate">Date</label>
                        <input type="date" id="eventDate" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="eventStart">Start Time</label>
                            <input type="time" id="eventStart" required>
                        </div>
                        <div class="form-group">
                            <label for="eventEnd">End Time</label>
                            <input type="time" id="eventEnd" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="eventDescription">Description</label>
                        <textarea id="eventDescription" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="eventColor">Color</label>
                        <div class="color-picker">
                            <input type="radio" name="eventColor" value="#667eea" id="color1" checked>
                            <label for="color1" class="color-option" style="background: #667eea;"></label>

                            <input type="radio" name="eventColor" value="#10b981" id="color2">
                            <label for="color2" class="color-option" style="background: #10b981;"></label>

                            <input type="radio" name="eventColor" value="#f59e0b" id="color3">
                            <label for="color3" class="color-option" style="background: #f59e0b;"></label>

                            <input type="radio" name="eventColor" value="#ef4444" id="color4">
                            <label for="color4" class="color-option" style="background: #ef4444;"></label>

                            <input type="radio" name="eventColor" value="#8b5cf6" id="color5">
                            <label for="color5" class="color-option" style="background: #8b5cf6;"></label>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" id="deleteEvent"
                            style="display: none;">Delete</button>
                        <button type="submit" class="btn btn-primary">Save Event</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        // Interactive Calendar Widget
        class InteractiveCalendar {
            constructor() {
                this.currentDate = new Date();
                this.selectedDate = new Date();
                this.currentView = 'month';
                this.events = this.loadEvents();
                this.draggedEvent = null;

                this.initializeElements();
                this.bindEvents();
                this.renderCalendar();
                this.renderEvents();
            }

            initializeElements() {
                // Header elements
                this.prevMonthBtn = document.getElementById('prevMonth');
                this.nextMonthBtn = document.getElementById('nextMonth');
                this.currentMonthEl = document.getElementById('currentMonth');
                this.viewBtns = document.querySelectorAll('.btn-view');

                // Calendar views
                this.monthView = document.getElementById('monthView');
                this.weekView = document.getElementById('weekView');
                this.dayView = document.getElementById('dayView');
                this.monthGrid = document.getElementById('monthGrid');
                this.weekGrid = document.getElementById('weekGrid');
                this.dayTimeline = document.getElementById('dayTimeline');

                // Event panel
                this.eventPanel = document.getElementById('eventPanel');
                this.addEventBtn = document.getElementById('addEventBtn');
                this.eventsList = document.getElementById('eventsList');

                // Modal
                this.eventModal = document.getElementById('eventModal');
                this.closeModal = document.getElementById('closeModal');
                this.eventForm = document.getElementById('eventForm');
                this.modalTitle = document.getElementById('modalTitle');
                this.deleteEventBtn = document.getElementById('deleteEvent');

                // Form elements
                this.eventTitle = document.getElementById('eventTitle');
                this.eventDate = document.getElementById('eventDate');
                this.eventStart = document.getElementById('eventStart');
                this.eventEnd = document.getElementById('eventEnd');
                this.eventDescription = document.getElementById('eventDescription');

                this.editingEventId = null;
            }

            bindEvents() {
                // Navigation
                this.prevMonthBtn.addEventListener('click', () => this.navigateMonth(-1));
                this.nextMonthBtn.addEventListener('click', () => this.navigateMonth(1));

                // View switching
                this.viewBtns.forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        this.switchView(e.target.dataset.view);
                    });
                });

                // Event management
                this.addEventBtn.addEventListener('click', () => this.openEventModal());
                this.closeModal.addEventListener('click', () => this.closeEventModal());
                this.eventForm.addEventListener('submit', (e) => this.handleEventSubmit(e));
                this.deleteEventBtn.addEventListener('click', () => this.deleteEvent());

                // Modal backdrop click
                this.eventModal.addEventListener('click', (e) => {
                    if (e.target === this.eventModal) {
                        this.closeEventModal();
                    }
                });

                // Keyboard shortcuts
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') {
                        this.closeEventModal();
                    }
                });
            }

            navigateMonth(direction) {
                this.currentDate.setMonth(this.currentDate.getMonth() + direction);
                this.renderCalendar();
                this.renderEvents();
            }

            switchView(view) {
                this.currentView = view;

                // Update active button
                this.viewBtns.forEach(btn => {
                    btn.classList.remove('active');
                    if (btn.dataset.view === view) {
                        btn.classList.add('active');
                    }
                });

                // Show/hide views
                this.monthView.classList.remove('active');
                this.weekView.classList.remove('active');
                this.dayView.classList.remove('active');

                switch (view) {
                    case 'month':
                        this.monthView.classList.add('active');
                        this.renderMonthView();
                        break;
                    case 'week':
                        this.weekView.classList.add('active');
                        this.renderWeekView();
                        break;
                    case 'day':
                        this.dayView.classList.add('active');
                        this.renderDayView();
                        break;
                }

                this.renderEvents();
            }

            renderCalendar() {
                this.currentMonthEl.textContent = this.currentDate.toLocaleDateString('en-US', {
                    month: 'long',
                    year: 'numeric'
                });

                this.renderMonthView();
            }

            renderMonthView() {
                const year = this.currentDate.getFullYear();
                const month = this.currentDate.getMonth();

                const firstDay = new Date(year, month, 1);
                const lastDay = new Date(year, month + 1, 0);
                const startDate = new Date(firstDay);
                startDate.setDate(startDate.getDate() - firstDay.getDay());

                this.monthGrid.innerHTML = '';

                for (let i = 0; i < 42; i++) {
                    const date = new Date(startDate);
                    date.setDate(startDate.getDate() + i);

                    const dayElement = this.createDayElement(date, month);
                    this.monthGrid.appendChild(dayElement);
                }
            }

            createDayElement(date, currentMonth) {
                const dayDiv = document.createElement('div');
                dayDiv.className = 'calendar-day';
                dayDiv.dataset.date = date.toISOString().split('T')[0];

                const dayNumber = document.createElement('div');
                dayNumber.className = 'day-number';
                dayNumber.textContent = date.getDate();

                const dayEvents = document.createElement('div');
                dayEvents.className = 'day-events';

                dayDiv.appendChild(dayNumber);
                dayDiv.appendChild(dayEvents);

                // Add classes
                if (date.getMonth() !== currentMonth) {
                    dayDiv.classList.add('other-month');
                }

                if (this.isToday(date)) {
                    dayDiv.classList.add('today');
                }

                if (this.isSelected(date)) {
                    dayDiv.classList.add('selected');
                }

                // Add click event
                dayDiv.addEventListener('click', () => {
                    this.selectDate(date);
                });

                return dayDiv;
            }

            renderWeekView() {
                const startOfWeek = this.getStartOfWeek(this.selectedDate);
                this.weekGrid.innerHTML = '';

                // Create time slots (6 AM to 10 PM)
                for (let hour = 6; hour <= 22; hour++) {
                    const timeSlot = document.createElement('div');
                    timeSlot.className = 'time-slot';
                    timeSlot.style.gridRow = `span 1`;

                    const timeLabel = document.createElement('div');
                    timeLabel.className = 'time-label';
                    timeLabel.textContent = this.formatHour(hour);

                    timeSlot.appendChild(timeLabel);
                    this.weekGrid.appendChild(timeSlot);
                }
            }

            renderDayView() {
                this.dayTimeline.innerHTML = '';

                // Create time slots (6 AM to 10 PM)
                for (let hour = 6; hour <= 22; hour++) {
                    const timeSlot = document.createElement('div');
                    timeSlot.className = 'day-time-slot';

                    const timeLabel = document.createElement('div');
                    timeLabel.className = 'day-time-label';
                    timeLabel.textContent = this.formatHour(hour);

                    timeSlot.appendChild(timeLabel);
                    this.dayTimeline.appendChild(timeSlot);
                }
            }

            renderEvents() {
                // Clear existing events
                this.clearEventDisplay();

                // Render events based on current view
                switch (this.currentView) {
                    case 'month':
                        this.renderMonthEvents();
                        break;
                    case 'week':
                        this.renderWeekEvents();
                        break;
                    case 'day':
                        this.renderDayEvents();
                        break;
                }

                // Render events list
                this.renderEventsList();
            }

            renderMonthEvents() {
                const dayElements = this.monthGrid.querySelectorAll('.calendar-day');

                dayElements.forEach(dayEl => {
                    const date = dayEl.dataset.date;
                    const dayEvents = this.getEventsForDate(date);
                    const eventsContainer = dayEl.querySelector('.day-events');

                    dayEvents.forEach(event => {
                        const eventDot = document.createElement('div');
                        eventDot.className = 'event-dot';
                        eventDot.style.background = event.color;
                        eventDot.textContent = event.title;
                        eventDot.title = `${event.title} - ${event.startTime}`;

                        eventDot.addEventListener('click', (e) => {
                            e.stopPropagation();
                            this.openEventModal(event);
                        });

                        eventsContainer.appendChild(eventDot);
                    });
                });
            }

            renderWeekEvents() {
                const startOfWeek = this.getStartOfWeek(this.selectedDate);

                for (let i = 0; i < 7; i++) {
                    const date = new Date(startOfWeek);
                    date.setDate(startOfWeek.getDate() + i);
                    const dateStr = date.toISOString().split('T')[0];
                    const dayEvents = this.getEventsForDate(dateStr);

                    dayEvents.forEach(event => {
                        const eventElement = this.createWeekEventElement(event, i);
                        this.weekGrid.appendChild(eventElement);
                    });
                }
            }

            renderDayEvents() {
                const dateStr = this.selectedDate.toISOString().split('T')[0];
                const dayEvents = this.getEventsForDate(dateStr);

                dayEvents.forEach(event => {
                    const eventElement = this.createDayEventElement(event);
                    this.dayTimeline.appendChild(eventElement);
                });
            }

            createWeekEventElement(event, dayIndex) {
                const eventDiv = document.createElement('div');
                eventDiv.className = 'week-event';
                eventDiv.style.background = event.color;
                eventDiv.style.gridColumn = dayIndex + 2; // +2 because first column is time
                eventDiv.style.gridRow = this.getTimeSlot(event.startTime);
                eventDiv.textContent = event.title;

                eventDiv.addEventListener('click', () => {
                    this.openEventModal(event);
                });

                return eventDiv;
            }

            createDayEventElement(event) {
                const eventDiv = document.createElement('div');
                eventDiv.className = 'day-event';
                eventDiv.style.background = event.color;
                eventDiv.textContent = `${event.title} (${event.startTime} - ${event.endTime})`;

                eventDiv.addEventListener('click', () => {
                    this.openEventModal(event);
                });

                return eventDiv;
            }

            renderEventsList() {
                this.eventsList.innerHTML = '';

                const sortedEvents = this.events.sort((a, b) => new Date(a.date) - new Date(b.date));

                sortedEvents.forEach(event => {
                    const eventItem = this.createEventListItem(event);
                    this.eventsList.appendChild(eventItem);
                });
            }

            createEventListItem(event) {
                const eventDiv = document.createElement('div');
                eventDiv.className = 'event-item';

                const colorDot = document.createElement('div');
                colorDot.className = 'event-color';
                colorDot.style.background = event.color;

                const eventInfo = document.createElement('div');
                eventInfo.className = 'event-info';

                const eventTitle = document.createElement('div');
                eventTitle.className = 'event-title';
                eventTitle.textContent = event.title;

                const eventTime = document.createElement('div');
                eventTime.className = 'event-time';
                eventTime.textContent = `${this.formatDate(event.date)} - ${event.startTime}`;

                eventInfo.appendChild(eventTitle);
                eventInfo.appendChild(eventTime);

                const eventActions = document.createElement('div');
                eventActions.className = 'event-actions';

                const editBtn = document.createElement('button');
                editBtn.className = 'edit-btn';
                editBtn.innerHTML = '<i class="fas fa-edit"></i>';
                editBtn.addEventListener('click', () => this.openEventModal(event));

                const deleteBtn = document.createElement('button');
                deleteBtn.className = 'delete-btn';
                deleteBtn.innerHTML = '<i class="fas fa-trash"></i>';
                deleteBtn.addEventListener('click', () => this.deleteEventById(event.id));

                eventActions.appendChild(editBtn);
                eventActions.appendChild(deleteBtn);

                eventDiv.appendChild(colorDot);
                eventDiv.appendChild(eventInfo);
                eventDiv.appendChild(eventActions);

                return eventDiv;
            }

            openEventModal(event = null) {
                this.editingEventId = event ? event.id : null;

                if (event) {
                    this.modalTitle.textContent = 'Edit Event';
                    this.eventTitle.value = event.title;
                    this.eventDate.value = event.date;
                    this.eventStart.value = event.startTime;
                    this.eventEnd.value = event.endTime;
                    this.eventDescription.value = event.description || '';

                    // Set color
                    const colorRadio = document.querySelector(`input[name="eventColor"][value="${event.color}"]`);
                    if (colorRadio) colorRadio.checked = true;

                    this.deleteEventBtn.style.display = 'block';
                } else {
                    this.modalTitle.textContent = 'Add New Event';
                    this.eventForm.reset();
                    this.eventDate.value = this.selectedDate.toISOString().split('T')[0];
                    this.deleteEventBtn.style.display = 'none';
                }

                this.eventModal.classList.add('active');
            }

            closeEventModal() {
                this.eventModal.classList.remove('active');
                this.editingEventId = null;
            }

            handleEventSubmit(e) {
                e.preventDefault();

                const eventData = {
                    title: this.eventTitle.value,
                    date: this.eventDate.value,
                    startTime: this.eventStart.value,
                    endTime: this.eventEnd.value,
                    description: this.eventDescription.value,
                    color: document.querySelector('input[name="eventColor"]:checked').value
                };

                if (this.editingEventId) {
                    this.updateEvent(this.editingEventId, eventData);
                } else {
                    this.addEvent(eventData);
                }

                this.closeEventModal();
            }

            addEvent(eventData) {
                const newEvent = {
                    id: Date.now().toString(),
                    ...eventData
                };

                this.events.push(newEvent);
                this.saveEvents();
                this.renderEvents();
            }

            updateEvent(eventId, eventData) {
                const eventIndex = this.events.findIndex(e => e.id === eventId);
                if (eventIndex !== -1) {
                    this.events[eventIndex] = { ...this.events[eventIndex], ...eventData };
                    this.saveEvents();
                    this.renderEvents();
                }
            }

            deleteEvent() {
                if (this.editingEventId) {
                    this.deleteEventById(this.editingEventId);
                    this.closeEventModal();
                }
            }

            deleteEventById(eventId) {
                this.events = this.events.filter(e => e.id !== eventId);
                this.saveEvents();
                this.renderEvents();
            }

            selectDate(date) {
                this.selectedDate = date;
                this.renderCalendar();
                this.renderEvents();
            }

            // Utility methods
            isToday(date) {
                const today = new Date();
                return date.toDateString() === today.toDateString();
            }

            isSelected(date) {
                return date.toDateString() === this.selectedDate.toDateString();
            }

            getStartOfWeek(date) {
                const start = new Date(date);
                const day = start.getDay();
                start.setDate(start.getDate() - day);
                return start;
            }

            getEventsForDate(date) {
                return this.events.filter(event => event.date === date);
            }

            formatHour(hour) {
                return hour < 12 ? `${hour} AM` : hour === 12 ? '12 PM' : `${hour - 12} PM`;
            }

            formatDate(dateStr) {
                return new Date(dateStr).toLocaleDateString('en-US', {
                    month: 'short',
                    day: 'numeric'
                });
            }

            getTimeSlot(timeStr) {
                const [hours, minutes] = timeStr.split(':').map(Number);
                return Math.max(1, hours - 5); // 6 AM = slot 1
            }

            clearEventDisplay() {
                // Clear month view events
                const dayEvents = this.monthGrid.querySelectorAll('.day-events');
                dayEvents.forEach(container => {
                    container.innerHTML = '';
                });

                // Clear week view events
                const weekEvents = this.weekGrid.querySelectorAll('.week-event');
                weekEvents.forEach(event => event.remove());

                // Clear day view events
                const dayViewEvents = this.dayTimeline.querySelectorAll('.day-event');
                dayViewEvents.forEach(event => event.remove());
            }

            // Local storage methods
            saveEvents() {
                localStorage.setItem('calendarEvents', JSON.stringify(this.events));
            }

            loadEvents() {
                const saved = localStorage.getItem('calendarEvents');
                return saved ? JSON.parse(saved) : [];
            }
        }

        // Initialize calendar when DOM is loaded
        document.addEventListener('DOMContentLoaded', () => {
            const calendar = new InteractiveCalendar();

            // Make calendar globally accessible for debugging
            window.calendar = calendar;
        });
    </script>
@endpush
