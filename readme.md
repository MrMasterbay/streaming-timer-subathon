# Livestream Timer Overlay

A sleek, modern countdown timer overlay for livestreams with real-time synchronization, admin controls, and embedded Twitch player. Perfect for gaming marathons, charity streams, or any timed streaming event.

![Preview](preview.png)

## Features

- **Real-time Countdown Timer** - Synchronized across all viewers via server-side state
- **Embedded Twitch Player** - Watch the stream directly in the overlay
- **Admin Panel** - Password-protected controls to manage the timer
- **Time Extension** - Add 5, 10, or 15 minutes on the fly (great for donation goals)
- **Pause/Resume** - Pause the timer during breaks without losing progress
- **Progress Bar** - Visual indicator showing stream completion percentage
- **Live Sync Indicator** - Shows connection status to the server
- **Fully Responsive** - Works on desktop, tablet, and mobile devices
- **Beautiful Animations** - Glowing orbs, shimmer effects, and smooth transitions

## Demo

The overlay displays:
- Stream branding and logo
- Live/Paused status badge
- Total marathon duration
- Countdown timer (hours, minutes, seconds)
- Start and end times
- Visual progress bar
- Embedded Twitch stream

## Installation

### Prerequisites

- Web server with PHP support (Apache, Nginx, etc.)
- PHP 7.0 or higher
- Write permissions for the data directory

### Setup

1. **Clone the repository**
   ```bash
   git clone https://github.com/MrMasterbay/streaming-timer-subathon.git
   cd streaming-timer-subathon
   ```

2. **Configure the API** (`api.php`)
   
   Open `api.php` and modify the following settings:
   ```php
   // Set your admin password
   $ADMIN_PASSWORD = 'CHANGEME';
   
   // Set your stream start time (Unix timestamp in milliseconds)
   $DEFAULT_START_TIME = strtotime('2025-12-31 20:00:00') * 1000;
   
   // Set your initial stream duration in milliseconds
   $DEFAULT_DURATION = (34 * 60 + 15) * 60 * 1000; // 34 hours 15 minutes
   ```

3. **Configure the Frontend** (`index.html`)
   
   Update the Twitch channel settings:
   ```html
   <!-- Change the Twitch channel in the iframe -->
   <iframe src="https://player.twitch.tv/?channel=YOURCHANNEL&parent=...">
   
   <!-- Update the Twitch link -->
   <a href="https://www.twitch.tv/YOURCHANNEL" target="_blank">
   ```
   
   Update stream information:
   ```html
   <!-- Update start date/time display -->
   <span>Start: 31.12.2025, 20:00 Uhr</span>
   ```

4. **Set file permissions**
   ```bash
   chmod 755 api.php
   chmod 755 data/
   chmod 644 data/timer.json
   ```

5. **Upload to your server**
   
   Upload all files to your web server's public directory.

## File Structure

```
livestream-timer-overlay/
├── index.html          # Main overlay page
├── api.php             # Backend API for timer state
├── data/
│   └── timer.json      # Persistent timer data (auto-generated)
└── README.md
```

## API Reference

### Endpoints

All endpoints use `api.php`.

#### GET `/api.php`

Returns current timer state.

**Response:**
```json
{
  "endTime": 1735804500000,
  "isPaused": false,
  "pausedTimeRemaining": null,
  "totalDuration": 123300000,
  "startTime": 1735681200000
}
```

#### POST `/api.php`

Modify timer state (requires authentication).

**Request Body:**
```json
{
  "password": "changeme",
  "action": "addTime",
  "minutes": 5
}
```

**Available Actions:**

| Action | Parameters | Description |
|--------|------------|-------------|
| `addTime` | `minutes` (int) | Adds time to the countdown |
| `pause` | - | Pauses the countdown |
| `resume` | - | Resumes the countdown |
| `reset` | - | Resets to initial state |

**Response:**
```json
{
  "success": true,
  "data": {
    "endTime": 1735804800000,
    "isPaused": false,
    "pausedTimeRemaining": null,
    "totalDuration": 123600000,
    "startTime": 1735681200000
  }
}
```

## Configuration Options

### Timer Settings

| Setting | Description | Default |
|---------|-------------|---------|
| `$ADMIN_PASSWORD` | Password for admin panel | `changeme` |
| `$DEFAULT_START_TIME` | Stream start timestamp (ms) | - |
| `$DEFAULT_DURATION` | Initial duration (ms) | 34h 15min |
| `$SYNC_INTERVAL` | Client sync interval | 2000ms |

### Customization

#### Colors

The overlay uses CSS custom properties. Main colors:
- Primary: `#9146ff` (Twitch purple)
- Secondary: `#ff4d6d` (Pink/Red)
- Accent: `#00f5d4` (Cyan)

## Admin Panel Usage

1. Click the **⚙️ Admin** button in the bottom right
2. Enter your admin password
3. Use the controls:
   - **+5 / +10 / +15 Min** - Add time to the countdown
   - **Pause/Resume** - Toggle timer state
   - **Logout** - Exit admin mode

When time is added, a visual notification appears on screen.

## Browser Support

- Chrome 80+
- Firefox 75+
- Safari 13+
- Edge 80+

## Troubleshooting

### Timer not syncing

- Check that `api.php` is accessible
- Verify PHP has write permissions to `data/` directory
- Check browser console for errors

### Admin login not working

- Verify the password in `api.php`
- Check the browser console for API errors
- Ensure POST requests are allowed on your server

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the **Creative Commons Attribution-NoDerivatives 4.0 International License (CC BY-ND 4.0)**.

### You are free to:

- **Share** — copy and redistribute the material in any medium or format for any purpose, even commercially

### Under the following terms:

- **Attribution** — You must give appropriate credit to the original author (**baGStube_Nico / Austrialetsplay1236**), provide a link to the license, and indicate if changes were made.

- **NoDerivatives** — If you remix, transform, or build upon the material, you may **not** distribute the modified material. This specifically includes:
  - Removing or modifying the original branding ("Zockstation")
  - Removing or modifying the credits section
  - Changing the attribution to the original author

### What you CAN customize:

- Twitch channel name
- Stream start/end times
- Admin password
- Timer duration

### What you CANNOT change:

- The credits footer ("Made with 💜 by baGStube_Nico alias Austrialetsplay1236")
- Attribution to the original author

See the [LICENSE](LICENSE) file for the full license text.

## Acknowledgments

- Inspired by gaming marathon streams
- Twitch Embed API for the player integration
- The streaming community for feedback and ideas

---

**Created by baGStube_Nico (Austrialetsplay1236)**

**Made with 💜 for streamers everywhere**
