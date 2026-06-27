# ImpactOps Maestro — Demo Video Render Notes

## Deliverables (in this folder)
- `ImpactOps-Maestro-demo.mp4` — **final, uploadable** (1920×1080, H.264 + AAC, 4:54, ~58 MB). Voiceover + ambient music bed.
- `animatic-silent.mp4` — backup silent render (same visuals, no audio, ~51 MB).
- `voiceover.mp3` — standalone narration track (294 s, loudness-normalized to −16 LUFS, ~3.3 MB).

## How it was made
1. The cinematic animatic `docs/agenthack-video-animatic.html` was played in real time in headless
   Chrome (Playwright / Chrome-for-Testing 1228) at 1920×1080 and captured at 12 fps → 3528 JPEG frames.
   (Captures the live CSS animations, count-ups, water drift, captions, chips, and progress bar.)
2. Frames encoded to `animatic-silent.mp4` with ffmpeg (libx264, CRF 19, output 30 fps).
3. Voiceover synthesized from `docs/agenthack-voiceover.md` using **Piper TTS** — a fully
   open-source, local neural text-to-speech engine — with the **en_US-lessac-medium** voice.
   Split into 18 per-scene segments, each placed at its scene cue, then mixed under the music bed and
   loudness-normalized to −16 LUFS. The long architecture explainer (seg 03) runs ~43 s on this voice,
   so segs 03–09 are nudged back to avoid overlap; the back half (Field Proof, Finance, Ledger, CSR,
   close) re-syncs exactly to the scene cues. Total stays at 4:54 (last word ends ~292 s).
   No cloud TTS, no credits, no upload — everything runs offline on-device.
   (The earlier macOS `say`/Samantha cut is kept as `ImpactOps-Maestro-demo-samantha.mp4`; the
   immediately previous final is backed up as `ImpactOps-Maestro-demo-prev.mp4`.)
4. A soft enterprise ambient pad was synthesized in ffmpeg (warm low chord, tremolo, low-pass, slow
   fades) sitting well under the voice.
5. Final mux: `voiceover + musicbed → loudnorm → AAC`, muxed onto the silent video.

## Re-render in one shot
The whole pipeline lives in the scratchpad used during the build. To re-render the **visuals** only:

```bash
# 1. capture frames (real time, ~5 min)
python3 capture.py 294 0           # writes frames/f%05d.jpg
# 2. encode silent base
ffmpeg -y -framerate 12 -i frames/f%05d.jpg -c:v libx264 -pix_fmt yuv420p -r 30 \
  -vf scale=1920:1080:flags=lanczos -crf 19 -preset medium -movflags +faststart animatic-silent.mp4
```

To re-render the **Piper voiceover** end-to-end (synthesize all 18 segments → place → mix → mux):
```bash
# from the build scratchpad (Piper venv + en_US-lessac-medium.onnx live there):
piper-venv/bin/python render_piper.py     # writes finalaudio_piper.wav + voiceover_piper.mp3
ffmpeg -y -i animatic-silent.mp4 -i finalaudio_piper.wav -c:v copy -c:a aac -b:a 192k \
  -shortest -movflags +faststart ImpactOps-Maestro-demo.mp4
```
To swap to a **different Piper voice**, download another ONNX voice and point the model at it
(one line — change the `-m` model path):
```bash
echo "TEXT" | piper-venv/bin/python -m piper -m <voice>.onnx -f out.wav
```
(e.g. `en_GB-northern_english_male-medium.onnx`, `en_US-ryan-high.onnx` — then re-run `render_piper.py` with that `MODEL`.)

To re-mux with a NEW/better voiceover (`new-vo.wav`):
```bash
ffmpeg -y -i new-vo.wav -i musicbed.wav -filter_complex \
 "[0:a]volume=1[v];[1:a]volume=1[m];[v][m]amix=inputs=2:normalize=0,loudnorm=I=-16:TP=-1.5:LRA=11[a]" \
 -map "[a]" -ar 48000 -ac 2 finalaudio.wav
ffmpeg -y -i animatic-silent.mp4 -i finalaudio.wav -c:v copy -c:a aac -b:a 192k \
 -shortest -movflags +faststart ImpactOps-Maestro-demo.mp4
```

## Public-safety
Sandbox/demo data only (Vikasana demo tenant, Acme CSR sandbox, localhost URLs, masked token,
illustrative amounts). No secrets, no real PII. Safe to upload as unlisted-public.
