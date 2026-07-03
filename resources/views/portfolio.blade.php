<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>{{ $settings['name'] }} — AI Command Center</title>
<meta name="description" content="A cinematic AI-powered introduction to {{ $settings['name'] }} — Backend Software Engineer building scalable systems, APIs, and digital experiences that power modern products."/>
<meta property="og:title" content="{{ $settings['name'] }} — AI Command Center"/>
<meta property="og:description" content="The AI is booting. Prepare for introduction."/>
<meta name="theme-color" content="#020308"/>
<meta name="csrf-token" content="{{ csrf_token() }}"/>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
<style>
/* ============================= TOKENS ============================= */
:root{
  --void:#020308; --deep:#060A14;
  --glass:rgba(255,255,255,.04);
  --line:rgba(120,160,255,.14); --line-hot:rgba(120,200,255,.42);
  --text:#F4F7FF; --muted:#B5BED6; --faint:#78839D;
  --cyan:#3DE8FF; --blue:#4D7CFE; --violet:#9D6BFF;
  --gold:#FFC56E; --green:#54F0A8; --rose:#FF6B9D;
  --grad:linear-gradient(105deg,#3DE8FF,#4D7CFE 45%,#9D6BFF);
  --display:'Space Grotesk',system-ui,sans-serif;
  --body:'Inter',system-ui,sans-serif;
  --mono:'JetBrains Mono',ui-monospace,monospace;
  --ease:cubic-bezier(.22,1,.36,1);
}
*{margin:0;padding:0;box-sizing:border-box}
html.lenis,html.lenis body{height:auto}
.lenis.lenis-smooth{scroll-behavior:auto!important}
body{
  background:
    radial-gradient(1200px 800px at 75% -10%, rgba(77,124,254,.10), transparent 60%),
    radial-gradient(1000px 700px at 10% 30%, rgba(157,107,255,.07), transparent 60%),
    var(--void);
  color:var(--text);font-family:var(--body);font-size:16px;line-height:1.65;
  overflow-x:hidden;-webkit-font-smoothing:antialiased;
}
::selection{background:rgba(61,232,255,.3)}
a{color:inherit;text-decoration:none}
button{font:inherit;color:inherit;background:none;border:none;cursor:pointer}
:focus-visible{outline:2px solid var(--cyan);outline-offset:3px;border-radius:4px}

/* ============================= AMBIENT LAYERS ============================= */
#net,#burst{position:fixed;inset:0;pointer-events:none}
#net{z-index:0}#burst{z-index:2500}
.gridfloor{
  position:fixed;left:-30%;right:-30%;bottom:-8%;height:42vh;z-index:0;pointer-events:none;
  transform:perspective(620px) rotateX(62deg);transform-origin:50% 100%;
  background:
    linear-gradient(rgba(61,232,255,.13) 1.5px, transparent 1.5px),
    linear-gradient(90deg, rgba(61,232,255,.13) 1.5px, transparent 1.5px);
  background-size:54px 54px;
  -webkit-mask-image:linear-gradient(transparent, #000 55%);
  mask-image:linear-gradient(transparent, #000 55%);
  animation:gridmove 3.2s linear infinite;
  opacity:.55;
}
@keyframes gridmove{to{background-position:0 54px, 0 54px}}
.noise{position:fixed;inset:-50%;width:200%;height:200%;pointer-events:none;z-index:2000;opacity:.035;
  background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='280' height='280'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='2'/%3E%3C/filter%3E%3Crect width='280' height='280' filter='url(%23n)'/%3E%3C/svg%3E");
  animation:grain 8s steps(10) infinite}
@keyframes grain{0%,100%{transform:translate(0,0)}20%{transform:translate(-4%,3%)}40%{transform:translate(3%,-4%)}60%{transform:translate(-3%,-3%)}80%{transform:translate(4%,3%)}}
.vignette{position:fixed;inset:0;pointer-events:none;z-index:1;background:radial-gradient(ellipse at center,transparent 55%,rgba(0,0,0,.5))}

.cur-dot,.cur-ring{position:fixed;top:0;left:0;z-index:3000;pointer-events:none;border-radius:50%;transform:translate(-50%,-50%)}
.cur-dot{width:5px;height:5px;background:var(--cyan);box-shadow:0 0 10px var(--cyan)}
.cur-ring{width:34px;height:34px;border:1px solid rgba(120,200,255,.4);transition:width .3s var(--ease),height .3s var(--ease),border-color .3s,background .3s}
.cur-ring.hov{width:60px;height:60px;border-color:var(--cyan);background:rgba(61,232,255,.06)}
@media (hover:none),(pointer:coarse){.cur-dot,.cur-ring{display:none}}

/* ============================= INTRO ============================= */
#intro{position:fixed;inset:0;z-index:5000;background:var(--void);overflow:hidden}
#introCanvas,#bigName,#warp{position:absolute;inset:0;width:100%;height:100%}
#warp{z-index:1}#bigName{z-index:2}#introCanvas{z-index:3}
.intro-frame{position:absolute;inset:16px;border:1px solid rgba(120,160,255,.1);pointer-events:none;z-index:4}
.intro-frame:before,.intro-frame:after{content:"";position:absolute;width:26px;height:26px;border-color:var(--cyan);border-style:solid}
.intro-frame:before{top:-1px;left:-1px;border-width:2px 0 0 2px}
.intro-frame:after{bottom:-1px;right:-1px;border-width:0 2px 2px 0}
#introStage{position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:24px;z-index:5}
.boot{font-family:var(--mono);font-size:clamp(11px,1.3vw,13.5px);line-height:2.15;color:var(--muted);width:min(560px,86vw)}
.boot .ln{display:flex;justify-content:space-between;gap:16px;opacity:0}
.boot .ln em{font-style:normal;color:var(--green)}
.boot .sys{color:var(--cyan)}
#holoWrap{position:relative;width:min(230px,50vw);opacity:0;filter:drop-shadow(0 0 30px rgba(61,232,255,.35))}
#holoWrap.assembled{animation:holoIdle 4s ease-in-out infinite}
@keyframes holoIdle{0%,100%{transform:translateY(0)}50%{transform:translateY(-9px)}}
#holoWrap.flicker{animation:holoFlick .8s steps(2) 1, holoIdle 4s ease-in-out .8s infinite}
@keyframes holoFlick{0%{opacity:.2}15%{opacity:1}25%{opacity:.4}40%{opacity:1}55%{opacity:.55}70%{opacity:1}85%{opacity:.7}100%{opacity:1}}
#holo{width:100%;display:block}
.scanlines{position:absolute;inset:0;pointer-events:none;mix-blend-mode:screen;
  background:repeating-linear-gradient(transparent 0 3px, rgba(61,232,255,.09) 3px 4px);
  animation:scanShift 6s linear infinite}
@keyframes scanShift{to{background-position:0 40px}}
.holo-base{position:absolute;left:50%;bottom:-26px;width:130%;height:34px;transform:translateX(-50%);
  background:radial-gradient(ellipse at center, rgba(61,232,255,.4), transparent 70%);filter:blur(6px);
  animation:basePulse 2.6s ease-in-out infinite}
@keyframes basePulse{50%{opacity:.5;transform:translateX(-50%) scaleX(.85)}}
.scan-ring{position:absolute;left:50%;top:50%;width:135%;aspect-ratio:1;transform:translate(-50%,-50%);
  border:1.5px solid rgba(61,232,255,.35);border-radius:50%;opacity:0;pointer-events:none}
.talk-burst{position:absolute;left:50%;top:42%;width:86%;aspect-ratio:1;transform:translate(-50%,-50%) scale(.74);
  border-radius:50%;border:1px solid rgba(61,232,255,.28);opacity:0;pointer-events:none}
.eye2{animation:blink2 4.4s infinite}
@keyframes blink2{0%,93%,100%{transform:scaleY(1)}95%,97%{transform:scaleY(.08)}}
.console{width:min(640px,90vw);min-height:112px;border:1px solid var(--line-hot);border-radius:16px;
  background:rgba(5,9,20,.78);backdrop-filter:blur(14px);
  box-shadow:0 20px 60px rgba(0,0,0,.6),0 0 36px rgba(61,232,255,.08),inset 0 1px 0 rgba(255,255,255,.06);
  padding:18px 22px;opacity:0}
.console-tag{display:flex;align-items:center;gap:9px;font-family:var(--mono);font-size:9.5px;letter-spacing:.3em;color:var(--cyan);margin-bottom:10px}
.console-tag i{width:7px;height:7px;border-radius:50%;background:var(--cyan);box-shadow:0 0 10px var(--cyan);animation:pulse 1.1s infinite}
@keyframes pulse{50%{opacity:.3}}
.console-tag .wave{display:inline-flex;gap:2.5px;margin-left:auto;align-items:flex-end;height:12px}
.console-tag .wave i{width:2.5px;border-radius:2px;background:var(--cyan);box-shadow:none;animation:eq .7s ease-in-out infinite}
.console-tag .wave i:nth-child(1){height:5px}.console-tag .wave i:nth-child(2){height:11px;animation-delay:.12s}
.console-tag .wave i:nth-child(3){height:7px;animation-delay:.24s}.console-tag .wave i:nth-child(4){height:10px;animation-delay:.36s}
@keyframes eq{50%{transform:scaleY(.35)}}
#sayPrev{font-family:var(--mono);font-size:11px;color:var(--faint);line-height:1.8;max-height:62px;overflow:hidden;display:flex;flex-direction:column-reverse}
#sayNow{font-family:var(--display);font-size:clamp(15px,2.1vw,19.5px);font-weight:500;color:var(--text);min-height:1.6em}
#sayNow .gold{color:var(--gold)}
.tcaret{display:inline-block;width:9px;height:1.05em;background:var(--cyan);vertical-align:-2px;margin-left:2px;animation:pulse 1s steps(1) infinite}
.diag{width:min(640px,90vw);display:flex;flex-direction:column;gap:6px;font-family:var(--mono);font-size:clamp(9.5px,1.15vw,11.5px);letter-spacing:.1em}
.diag .d{display:flex;justify-content:space-between;gap:14px;color:var(--muted);opacity:0;border:1px solid var(--line);border-radius:9px;padding:8px 14px;background:rgba(255,255,255,.02)}
.diag .d b{color:var(--green);font-weight:700;white-space:nowrap}
.diag .d.exc{border-color:rgba(255,197,110,.4)}
.diag .d.exc b{color:var(--gold)}
#scanBeam{position:absolute;left:0;right:0;top:-12%;height:10%;z-index:6;pointer-events:none;opacity:0;
  background:linear-gradient(transparent, rgba(61,232,255,.16) 45%, rgba(61,232,255,.5) 50%, rgba(61,232,255,.16) 55%, transparent);
  box-shadow:0 0 40px rgba(61,232,255,.25)}
#coreRing{position:absolute;left:50%;top:55%;width:60px;height:60px;z-index:6;pointer-events:none;
  border:2px solid var(--cyan);border-radius:50%;transform:translate(-50%,-50%);opacity:0;
  box-shadow:0 0 40px rgba(61,232,255,.6),inset 0 0 30px rgba(61,232,255,.3)}
#flash{position:absolute;inset:0;z-index:7;background:radial-gradient(circle at 50% 55%, #CFF6FF, #3DE8FF 40%, transparent 75%);opacity:0;pointer-events:none}
#enterGate{position:absolute;inset:0;z-index:60;display:flex;align-items:center;justify-content:center;text-align:center;background:
  radial-gradient(circle at 50% 38%,rgba(14,30,56,.96),rgba(2,3,10,.98) 72%),
  linear-gradient(135deg,rgba(61,232,255,.08),transparent 42%,rgba(157,107,255,.08));
  overflow:hidden}
.eg-bg{position:absolute;inset:0;pointer-events:none;opacity:.9}
.eg-bg:before{content:"RISHABH PAREKH";position:absolute;left:50%;top:49%;transform:translate(-50%,-50%);
  font-family:var(--display);font-size:clamp(58px,14vw,178px);font-weight:700;letter-spacing:.08em;white-space:nowrap;
  color:transparent;-webkit-text-stroke:1px rgba(120,200,255,.11);text-shadow:0 0 70px rgba(61,232,255,.08)}
.eg-bg:after{content:"";position:absolute;inset:0;background:
  linear-gradient(rgba(61,232,255,.09) 1px,transparent 1px),
  linear-gradient(90deg,rgba(61,232,255,.07) 1px,transparent 1px);
  background-size:72px 72px;mask-image:radial-gradient(circle at 50% 45%,#000 0 44%,transparent 76%);
  animation:egGrid 10s linear infinite}
.eg-card{position:absolute;min-width:168px;padding:13px 15px;border:1px solid rgba(120,200,255,.24);border-radius:10px;
  background:rgba(5,10,22,.54);box-shadow:0 18px 48px rgba(0,0,0,.28),0 0 30px rgba(61,232,255,.08);
  font-family:var(--mono);font-size:10px;letter-spacing:.12em;color:var(--muted);text-align:left;backdrop-filter:blur(10px);
  animation:egFloat 7s ease-in-out infinite}
.eg-card b{display:block;color:var(--cyan);font-size:11px;margin-bottom:4px}
.eg-card small{display:block;color:var(--faint);font-size:8.5px;letter-spacing:.16em;margin-top:3px}
.eg-card.c1{left:8%;top:18%}.eg-card.c2{right:9%;top:22%;animation-delay:-1.4s}.eg-card.c3{left:12%;bottom:18%;animation-delay:-2.6s}.eg-card.c4{right:12%;bottom:16%;animation-delay:-3.8s}
@keyframes egGrid{to{background-position:0 72px,72px 0}}
@keyframes egFloat{0%,100%{transform:translateY(0)}50%{transform:translateY(-14px)}}
.eg-box{position:relative;z-index:2;display:flex;flex-direction:column;align-items:center;gap:12px;padding:22px}
.eg-ring{width:74px;height:74px;border-radius:50%;border:2px solid var(--cyan);border-top-color:transparent;animation:egspin 1.1s linear infinite;opacity:.45;margin-bottom:8px}
@keyframes egspin{to{transform:rotate(360deg)}}
.eg-title{font-family:var(--display,sans-serif);font-weight:700;font-size:clamp(38px,7.2vw,76px);letter-spacing:.06em;background:var(--grad);-webkit-background-clip:text;background-clip:text;color:transparent;text-shadow:0 0 50px rgba(61,232,255,.18)}
.eg-sub{font-family:var(--mono);font-size:12px;letter-spacing:.16em;color:var(--text);text-transform:uppercase}
.eg-copy{max-width:520px;color:var(--muted);font-size:15px;line-height:1.65;margin-top:2px}
#enterBtn{margin-top:18px;font-family:var(--mono);font-size:14px;font-weight:700;letter-spacing:.08em;color:#03101A;background:linear-gradient(135deg,var(--cyan),var(--green));border:none;border-radius:999px;padding:15px 34px;cursor:pointer;box-shadow:0 0 32px rgba(61,232,255,.4);transition:.2s}
#enterBtn:hover{transform:translateY(-2px) scale(1.03);filter:brightness(1.06)}
.eg-hint{font-family:var(--mono);font-size:11px;letter-spacing:.08em;color:var(--muted);margin-top:8px}
#skipIntro{position:absolute;z-index:8;right:22px;bottom:20px;font-family:var(--mono);font-size:11px;letter-spacing:.18em;color:var(--faint);padding:10px 18px;border:1px solid var(--line);border-radius:999px;transition:.3s;background:rgba(2,3,8,.5)}
#skipIntro:hover{color:var(--text);border-color:var(--line-hot)}
#voiceToggle{position:fixed;z-index:6000;left:18px;bottom:18px;font-family:var(--mono);font-size:11px;letter-spacing:.14em;color:var(--cyan);padding:9px 14px;border:1px solid var(--line);border-radius:999px;transition:.3s;background:rgba(2,3,8,.55);backdrop-filter:blur(6px);cursor:pointer;display:flex;align-items:center;gap:7px}
#voiceToggle:hover{border-color:var(--line-hot);color:var(--text)}
#voiceToggle.off{color:var(--faint)}
#voiceToggle.speaking{border-color:var(--cyan);box-shadow:0 0 14px rgba(61,232,255,.35)}
#voiceToggle.speaking #voiceIco{animation:vpulse 1s infinite}
@keyframes vpulse{0%,100%{opacity:1}50%{opacity:.35}}

/* ============================= HUD + NAV ============================= */
.hud{position:fixed;top:0;left:0;right:0;z-index:1000;display:flex;align-items:center;gap:18px;
  padding:13px clamp(16px,3vw,30px);font-family:var(--mono);font-size:11px;letter-spacing:.12em;color:var(--muted);
  background:linear-gradient(rgba(2,3,8,.85),rgba(2,3,8,.4) 80%,transparent);
  backdrop-filter:blur(8px);border-bottom:1px solid rgba(120,160,255,.08);opacity:0;translate:0 -14px}
.hud .live{display:inline-flex;align-items:center;gap:7px;color:var(--green)}
.hud .live i{width:6px;height:6px;border-radius:50%;background:var(--green);box-shadow:0 0 10px var(--green);animation:pulse 2s infinite}
.hud .name{color:var(--text);font-weight:700}
.hud .sector{margin-left:auto;color:var(--cyan)}
.hud .pbar{width:120px;height:3px;border-radius:99px;background:rgba(255,255,255,.08);overflow:hidden}
.hud .pbar i{display:block;height:100%;width:0;background:var(--grad)}
.hud .pct{min-width:4ch;text-align:right}
@media(max-width:700px){.hud .hide-s{display:none}}
.waypoints{position:fixed;right:clamp(10px,2vw,24px);top:50%;transform:translateY(-50%);z-index:1000;display:flex;flex-direction:column;gap:16px;opacity:0}
.wp{position:relative;width:12px;height:12px;border-radius:50%;border:1.5px solid var(--faint);transition:.35s var(--ease);display:block}
.wp:hover{border-color:var(--cyan)}
.wp.active{border-color:var(--cyan);background:var(--cyan);box-shadow:0 0 14px var(--cyan)}
.wp span{position:absolute;right:24px;top:50%;transform:translateY(-50%);font-family:var(--mono);font-size:10px;letter-spacing:.16em;color:var(--cyan);white-space:nowrap;opacity:0;transition:.3s;pointer-events:none}
.wp:hover span,.wp.active span{opacity:1}
@media(max-width:820px){.waypoints{display:none}}

/* ============================= MODULES (sections) ============================= */
main{position:relative;z-index:2}
.module{position:relative;padding:clamp(110px,15vh,180px) clamp(20px,4vw,40px)}
.wrap{max-width:1120px;margin:0 auto;position:relative}
.sec-head{margin-bottom:clamp(40px,6vh,64px)}
.sec-tag{display:inline-flex;align-items:center;gap:12px;font-family:var(--mono);font-size:11.5px;letter-spacing:.3em;color:var(--cyan);margin-bottom:18px}
.sec-tag:before{content:"";width:34px;height:1px;background:var(--cyan);box-shadow:0 0 8px var(--cyan)}
.sec-title{font-family:var(--display);font-weight:700;font-size:clamp(34px,5.4vw,62px);line-height:1.04;letter-spacing:0}
.sec-title .g{background:var(--grad);-webkit-background-clip:text;background-clip:text;color:transparent}
.sec-sub{color:var(--muted);max-width:560px;margin-top:16px;font-size:clamp(14.5px,1.5vw,17px)}
[data-reveal]{opacity:0;transform:translateY(40px)}
.no-anim [data-reveal]{opacity:1!important;transform:none!important}
.glass{border:1px solid var(--line);border-radius:20px;
  background:linear-gradient(165deg,rgba(120,160,255,.07),rgba(10,16,30,.5) 45%);
  backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px);
  box-shadow:0 24px 70px rgba(0,0,0,.5),inset 0 1px 0 rgba(255,255,255,.07);
  position:relative;overflow:hidden;transform-style:preserve-3d;will-change:transform;transition:border-color .35s}
.glass:hover{border-color:var(--line-hot)}
.glass .spot{position:absolute;inset:0;pointer-events:none;opacity:0;transition:opacity .4s;
  background:radial-gradient(380px circle at var(--mx,50%) var(--my,50%),rgba(61,232,255,.13),transparent 65%)}
.glass:hover .spot{opacity:1}
.corner{position:absolute;width:14px;height:14px;border-color:rgba(61,232,255,.45);border-style:solid;pointer-events:none}
.c-tl{top:8px;left:8px;border-width:1.5px 0 0 1.5px}
.c-br{bottom:8px;right:8px;border-width:0 1.5px 1.5px 0}

/* hero */
.hero{min-height:100svh;display:flex;flex-direction:column;justify-content:center;align-items:center;text-align:center;padding-top:120px;overflow:hidden}
.hero:before{content:"";position:absolute;inset:0;z-index:-1;background:
  radial-gradient(circle at 18% 28%,rgba(84,240,168,.12),transparent 27%),
  radial-gradient(circle at 82% 30%,rgba(157,107,255,.13),transparent 29%),
  linear-gradient(115deg,transparent 0 37%,rgba(61,232,255,.06) 38% 39%,transparent 40% 100%)}
.project-backdrop{position:absolute;inset:0;z-index:-1;pointer-events:none;opacity:.72}
.project-backdrop:before{content:"RISHABH PAREKH";position:absolute;left:50%;top:48%;transform:translate(-50%,-50%);
  font-family:var(--display);font-size:clamp(70px,16vw,210px);font-weight:700;letter-spacing:.06em;line-height:.85;white-space:nowrap;
  color:rgba(255,255,255,.025);-webkit-text-stroke:1px rgba(61,232,255,.09)}
.pb-tile{position:absolute;width:190px;padding:13px 14px;border:1px solid rgba(120,200,255,.18);border-radius:10px;
  background:rgba(6,10,22,.45);backdrop-filter:blur(10px);box-shadow:0 18px 46px rgba(0,0,0,.24);
  font-family:var(--mono);font-size:10px;letter-spacing:.1em;color:var(--muted);text-align:left}
.pb-tile b{display:block;color:var(--cyan);font-size:11px;margin-bottom:4px}.pb-tile span{color:var(--green)}
.pb1{left:5%;top:28%}.pb2{right:6%;top:34%}.pb3{left:9%;bottom:22%}.pb4{right:10%;bottom:20%}
.hero-coord{font-family:var(--mono);font-size:11.5px;letter-spacing:.3em;color:var(--faint);margin-bottom:26px}
.hero h1{font-family:var(--display);font-weight:700;font-size:clamp(48px,9.5vw,118px);line-height:.98;letter-spacing:0;text-shadow:0 0 80px rgba(77,124,254,.35)}
.hero h1 .row{display:block;overflow:hidden}
.hero h1 .row>span{display:inline-block}
.hero h1 .g{background:var(--grad);-webkit-background-clip:text;background-clip:text;color:transparent}
.hero-role{font-family:var(--mono);font-size:clamp(12px,1.6vw,15px);letter-spacing:.34em;color:var(--cyan);margin-top:26px;text-indent:.34em}
.hero-quote{margin-top:18px;color:var(--muted);font-size:clamp(15px,1.7vw,19px);max-width:580px}
.hero-quote b{color:var(--text)}
.hero-ctas{display:flex;gap:14px;margin-top:40px;flex-wrap:wrap;justify-content:center}
.btn{position:relative;display:inline-flex;align-items:center;gap:10px;font-family:var(--mono);font-size:13px;font-weight:700;letter-spacing:.06em;padding:16px 30px;border-radius:999px;will-change:transform;transition:box-shadow .3s}
.btn-go{background:var(--grad);color:#03040A;box-shadow:0 10px 36px rgba(77,160,254,.4)}
.btn-go:hover{box-shadow:0 14px 50px rgba(61,232,255,.55)}
.btn-line{border:1px solid var(--line-hot);background:rgba(61,232,255,.05);color:var(--cyan)}
.holo-chip{position:absolute;font-family:var(--mono);font-size:10.5px;letter-spacing:.12em;color:var(--muted);
  padding:9px 15px;border-radius:12px;border:1px solid var(--line-hot);background:rgba(6,10,22,.7);backdrop-filter:blur(10px);
  box-shadow:0 12px 32px rgba(0,0,0,.4),0 0 22px rgba(61,232,255,.1);white-space:nowrap;will-change:transform}
.holo-chip b{color:var(--cyan);font-weight:500}
.hc-1{top:22%;left:8%}.hc-2{top:30%;right:7%}.hc-3{bottom:24%;left:12%}.hc-4{bottom:18%;right:10%}
@media(max-width:900px){.holo-chip,.eg-card,.pb-tile{display:none}.eg-bg:before,.project-backdrop:before{font-size:clamp(52px,18vw,120px);white-space:normal;width:min(92vw,720px)}}
.hero-scroll{position:absolute;bottom:26px;left:50%;transform:translateX(-50%);display:flex;flex-direction:column;align-items:center;gap:9px;font-family:var(--mono);font-size:10px;letter-spacing:.34em;color:var(--faint)}
.hero-scroll .beam{width:1px;height:54px;background:linear-gradient(var(--cyan),transparent);position:relative;overflow:hidden}
.hero-scroll .beam:after{content:"";position:absolute;top:-20px;left:0;width:100%;height:20px;background:var(--cyan);animation:fall 1.8s var(--ease) infinite}
@keyframes fall{to{transform:translateY(80px)}}

/* ===== MODULE 01 — PROFILE REPORT ===== */
.report{display:grid;grid-template-columns:.95fr 1.05fr;gap:0;border-radius:22px;overflow:hidden;border:1px solid var(--line-hot);
  background:linear-gradient(150deg,rgba(61,232,255,.07),rgba(6,10,22,.7) 40%,rgba(157,107,255,.06));
  backdrop-filter:blur(20px);box-shadow:0 36px 90px rgba(0,0,0,.55),inset 0 1px 0 rgba(255,255,255,.07)}
.report-id{padding:clamp(28px,3.5vw,42px);border-right:1px solid var(--line);position:relative;overflow:hidden}
.report-id .shimmer{position:absolute;inset:0;pointer-events:none;
  background:linear-gradient(115deg,transparent 35%,rgba(61,232,255,.10) 50%,transparent 65%);
  background-size:240% 100%;animation:shim 3.6s ease-in-out infinite}
@keyframes shim{0%{background-position:120% 0}100%{background-position:-120% 0}}
.report-tag{font-family:var(--mono);font-size:9.5px;letter-spacing:.34em;color:var(--gold);margin-bottom:20px}
.id-row{display:flex;justify-content:space-between;gap:14px;font-family:var(--mono);font-size:clamp(11px,1.2vw,12.5px);letter-spacing:.08em;
  padding:11px 0;border-bottom:1px dashed var(--line)}
.id-row:last-of-type{border-bottom:none}
.id-row .k{color:var(--faint)}
.id-row .v{color:var(--text);text-align:right}
.id-row .v.cy{color:var(--cyan)}.id-row .v.gn{color:var(--green)}
.report-body{padding:clamp(28px,3.5vw,42px);display:flex;flex-direction:column}
.report-body h3{font-family:var(--display);font-size:clamp(20px,2.4vw,26px);font-weight:600;margin-bottom:14px}
.report-body p{color:var(--muted);font-size:15px;margin-bottom:14px}
.report-body p b{color:var(--text);font-weight:600}
.report-note{margin-top:auto;padding-top:18px;font-family:var(--mono);font-size:10px;letter-spacing:.14em;color:var(--faint);border-top:1px dashed var(--line)}
.report-note b{color:var(--green)}
@media(max-width:880px){.report{grid-template-columns:1fr}.report-id{border-right:none;border-bottom:1px solid var(--line)}}

/* ===== MODULE 02 — SKILL MODULES ===== */
.modgrid-wrap{position:relative}
#neural{position:absolute;inset:0;width:100%;height:100%;pointer-events:none}
.modgrid{display:grid;grid-template-columns:repeat(6,1fr);gap:18px;position:relative}
.skillmod{padding:26px;grid-column:span 2;display:flex;flex-direction:column}
.skillmod.wide{grid-column:span 3}
.mod-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:18px}
.mod-head h3{font-family:var(--display);font-size:17.5px;font-weight:600;display:flex;align-items:center;gap:10px}
.mod-head .ic{width:34px;height:34px;border-radius:10px;display:grid;place-items:center;background:rgba(61,232,255,.1);border:1px solid rgba(61,232,255,.25);font-size:15px}
.mod-status{font-family:var(--mono);font-size:8.5px;letter-spacing:.24em;color:var(--green);display:flex;align-items:center;gap:6px}
.mod-status i{width:6px;height:6px;border-radius:50%;background:var(--green);box-shadow:0 0 9px var(--green);animation:pulse 1.8s infinite}
.mod-list{display:flex;flex-direction:column;gap:11px}
.mod-row .top{display:flex;justify-content:space-between;font-family:var(--mono);font-size:11.5px;margin-bottom:5px}
.mod-row .top em{font-style:normal;color:var(--cyan)}
.ebar{height:4px;border-radius:99px;background:rgba(255,255,255,.06);overflow:hidden;position:relative}
.ebar i{display:block;height:100%;width:0;border-radius:99px;background:var(--grad);position:relative;overflow:hidden}
.ebar i:after{content:"";position:absolute;top:0;bottom:0;width:34px;background:linear-gradient(90deg,transparent,rgba(255,255,255,.75),transparent);
  animation:energy 2.1s linear infinite}
@keyframes energy{from{left:-40px}to{left:110%}}
@media(max-width:980px){.skillmod,.skillmod.wide{grid-column:span 3}}
@media(max-width:720px){.skillmod,.skillmod.wide{grid-column:span 6}}

/* ===== MODULE 03 — EXPERIENCE DATABASE ===== */
.logs{display:flex;flex-direction:column;gap:30px;position:relative}
.logline{position:absolute;left:24px;top:10px;bottom:10px;width:2px;background:var(--line);border-radius:99px;overflow:hidden}
.logline i{position:absolute;inset:0;background:linear-gradient(#3DE8FF,#9D6BFF);transform:scaleY(0);transform-origin:top}
.log{margin-left:60px;padding:30px 30px 26px}
.log:before{content:"";position:absolute;left:-44px;top:34px;width:14px;height:14px;border-radius:50%;
  background:var(--void);border:2px solid var(--cyan);box-shadow:0 0 0 5px rgba(61,232,255,.12),0 0 16px rgba(61,232,255,.6)}
.log-id{font-family:var(--mono);font-size:10.5px;letter-spacing:.3em;color:var(--cyan);margin-bottom:12px;display:flex;align-items:center;gap:12px;flex-wrap:wrap}
.log-id .tx{margin-left:auto;color:var(--faint);letter-spacing:.1em;display:flex;align-items:center;gap:7px}
.log-id .tx i{width:6px;height:6px;border-radius:50%;background:var(--green);box-shadow:0 0 8px var(--green);animation:pulse 1.6s infinite}
.log h3{font-family:var(--display);font-size:clamp(21px,2.5vw,27px);font-weight:600}
.log .org-sub{font-family:var(--mono);font-size:11px;color:var(--faint);margin-top:2px}
.log-when{display:inline-block;margin-top:10px;font-family:var(--mono);font-size:11px;color:var(--gold);padding:5px 13px;border:1px solid rgba(255,197,110,.3);border-radius:999px;background:rgba(255,197,110,.06)}
.log-role{font-family:var(--mono);font-size:12px;color:var(--violet);margin-top:12px}
.resp{display:flex;flex-wrap:wrap;gap:9px;margin-top:16px}
.tag{font-family:var(--mono);font-size:10.5px;letter-spacing:.06em;color:var(--muted);padding:6px 13px;border:1px solid var(--line);border-radius:999px;background:rgba(255,255,255,.025);transition:.3s}
.tag:hover{color:var(--text);border-color:var(--line-hot)}
@media(max-width:640px){.log{margin-left:42px}.log:before{left:-32px}.logline{left:14px}}

/* ===== MODULE 04 — PROJECT COMMAND CENTER ===== */
.projects{display:grid;grid-template-columns:repeat(6,1fr);gap:18px}
.proj{padding:28px;display:flex;flex-direction:column;min-height:340px}
.pj-1{grid-column:span 4}.pj-2,.pj-3,.pj-4,.pj-5{grid-column:span 2}
.pj-kicker{font-family:var(--mono);font-size:10px;letter-spacing:.26em;color:var(--faint);text-transform:uppercase;display:flex;align-items:center;gap:8px;margin-bottom:10px}
.pj-kicker i{width:7px;height:7px;border-radius:50%;font-style:normal;animation:pulse 2s infinite}
.proj h3{font-family:var(--display);font-size:clamp(20px,2.2vw,25px);font-weight:600}
.proj>p{color:var(--muted);font-size:14px;max-width:460px;margin-top:6px}
/* architecture flow */
.arch{display:flex;align-items:center;gap:0;margin-top:20px;font-family:var(--mono)}
.node{flex:none;font-size:9.5px;letter-spacing:.1em;color:var(--text);padding:9px 12px;border:1px solid var(--line-hot);border-radius:9px;
  background:rgba(61,232,255,.05);text-align:center;line-height:1.4}
.node small{display:block;font-size:8px;color:var(--faint);letter-spacing:.14em}
.wire{flex:1;height:2px;background:rgba(120,160,255,.18);position:relative;min-width:26px;overflow:visible}
.wire i{position:absolute;top:-2px;left:0;width:6px;height:6px;border-radius:50%;background:var(--cyan);box-shadow:0 0 10px var(--cyan);
  animation:flow 1.6s linear infinite}
.wire i:nth-child(2){animation-delay:.8s}
@keyframes flow{from{left:-4px;opacity:0}12%{opacity:1}88%{opacity:1}to{left:calc(100% - 2px);opacity:0}}
.pj-metrics{display:flex;gap:24px;margin-top:18px}
.pj-metrics div b{display:block;font-family:var(--display);font-size:20px;font-weight:700;background:var(--grad);-webkit-background-clip:text;background-clip:text;color:transparent}
.pj-metrics div span{font-family:var(--mono);font-size:9.5px;letter-spacing:.12em;color:var(--faint)}
.pj-tags{display:flex;flex-wrap:wrap;gap:8px;margin-top:auto;padding-top:20px}
@media(max-width:920px){.projects .proj{grid-column:span 6!important}.proj{min-height:0}}

/* ===== MODULE 05 — ACHIEVEMENT TERMINAL ===== */
.terminal{border-radius:18px;overflow:hidden;border:1px solid var(--line-hot);
  background:rgba(4,7,16,.85);backdrop-filter:blur(16px);
  box-shadow:0 30px 80px rgba(0,0,0,.6),inset 0 1px 0 rgba(255,255,255,.06);font-family:var(--mono)}
.term-bar{display:flex;align-items:center;gap:8px;padding:13px 16px;border-bottom:1px solid var(--line);background:rgba(255,255,255,.02)}
.term-bar i{width:11px;height:11px;border-radius:50%}
.term-bar i:nth-child(1){background:#FF5F57}.term-bar i:nth-child(2){background:#FEBC2E}.term-bar i:nth-child(3){background:#28C840}
.term-bar span{margin-left:auto;font-size:10.5px;color:var(--faint);letter-spacing:.12em}
.term-body{padding:22px 24px;font-size:clamp(11px,1.3vw,13px);line-height:2}
.term-body .q{color:var(--cyan)}
.term-body .dim{color:var(--faint)}
.ach-rows{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;padding:6px 24px 26px}
.ach{border:1px solid var(--line);border-radius:14px;padding:22px 16px;text-align:center;background:rgba(255,255,255,.02);position:relative;overflow:hidden}
.ach .num{font-family:var(--display);font-weight:700;font-size:clamp(28px,3.4vw,40px);background:linear-gradient(105deg,#FFE3AE,#FFC56E,#FF9D5C);-webkit-background-clip:text;background-clip:text;color:transparent;line-height:1.15}
.ach .lab{font-family:var(--mono);font-size:9.5px;letter-spacing:.18em;color:var(--muted);margin-top:7px}
.ach .st{font-family:var(--mono);font-size:8.5px;letter-spacing:.3em;color:var(--green);margin-top:10px;opacity:0}
.no-anim .ach .st{opacity:1}
@media(max-width:860px){.ach-rows{grid-template-columns:repeat(2,1fr)}}

/* ===== MODULE 06 — CONTACT ===== */
.command{display:grid;grid-template-columns:1.05fr .95fr;border-radius:22px;overflow:hidden;border:1px solid var(--line-hot);
  background:linear-gradient(150deg,rgba(61,232,255,.08),rgba(6,10,22,.75) 38%,rgba(157,107,255,.07));
  backdrop-filter:blur(22px);box-shadow:0 40px 100px rgba(0,0,0,.6),inset 0 1px 0 rgba(255,255,255,.08),0 0 60px rgba(61,232,255,.07)}
.cmd-left{padding:clamp(30px,4vw,46px);border-right:1px solid var(--line);display:flex;flex-direction:column}
.cmd-left h3{font-family:var(--display);font-size:clamp(23px,2.8vw,31px);font-weight:600}
.cmd-left>p{color:var(--muted);font-size:14.5px;margin-top:10px;max-width:380px}
.ai-line{margin-top:22px;font-family:var(--mono);font-size:11px;letter-spacing:.1em;color:var(--cyan);
  border:1px dashed rgba(61,232,255,.3);border-radius:11px;padding:13px 16px;background:rgba(61,232,255,.04);line-height:1.9}
.ai-line b{color:var(--green)}
.cmd-stats{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:22px;font-family:var(--mono);font-size:10.5px;letter-spacing:.1em}
.cmd-stat{border:1px solid var(--line);border-radius:12px;padding:13px 15px;background:rgba(255,255,255,.02)}
.cmd-stat b{display:block;color:var(--cyan);font-size:13px;margin-bottom:3px}
.cmd-links{margin-top:auto;padding-top:26px;display:flex;flex-direction:column;gap:11px}
.cmd-link{display:flex;align-items:center;gap:13px;font-family:var(--mono);font-size:12.5px;color:var(--muted);transition:color .3s}
.cmd-link:hover{color:var(--text)}
.cmd-link .ic{width:38px;height:38px;flex:none;border-radius:10px;display:grid;place-items:center;border:1px solid var(--line);background:rgba(255,255,255,.03)}
.cmd-link svg{width:16px;height:16px;fill:currentColor}
.cmd-form{padding:clamp(30px,4vw,46px);display:flex;flex-direction:column;gap:20px}
.fld{position:relative}
.fld input,.fld textarea{width:100%;background:rgba(255,255,255,.03);border:1px solid var(--line);border-radius:11px;padding:16px 15px 14px;color:var(--text);font:14px/1.5 var(--body);resize:vertical;transition:.3s}
.fld textarea{min-height:110px}
.fld label{position:absolute;left:12px;top:15px;padding:0 6px;color:var(--faint);font-family:var(--mono);font-size:12.5px;pointer-events:none;transition:.25s var(--ease)}
.fld input:focus,.fld textarea:focus{outline:none;border-color:var(--line-hot);box-shadow:0 0 0 4px rgba(61,232,255,.1);background:rgba(61,232,255,.04)}
.fld input:focus+label,.fld input:not(:placeholder-shown)+label,
.fld textarea:focus+label,.fld textarea:not(:placeholder-shown)+label{top:-9px;font-size:9.5px;letter-spacing:.2em;color:var(--cyan);background:#070C18;border-radius:4px}
.launch{position:relative;overflow:hidden;justify-content:center;width:100%;background:var(--grad);color:#03040A;box-shadow:0 10px 36px rgba(61,180,255,.4)}
.launch .ok2{position:absolute;inset:0;display:grid;place-items:center;background:linear-gradient(105deg,#2BC780,#54F0A8);transform:translateY(101%);transition:transform .5s var(--ease);color:#03150C}
.launch.sent .ok2{transform:translateY(0)}
@media(max-width:880px){.command{grid-template-columns:1fr}.cmd-left{border-right:none;border-bottom:1px solid var(--line)}}

#complete{position:fixed;inset:0;z-index:4500;display:grid;place-items:center;background:rgba(2,3,8,.88);backdrop-filter:blur(14px);opacity:0;pointer-events:none;transition:opacity .5s}
#complete.show{opacity:1;pointer-events:auto}
.complete-box{text-align:center;padding:30px}
.complete-box .t{font-family:var(--mono);font-size:11px;letter-spacing:.5em;color:var(--green);text-indent:.5em}
.complete-box h2{font-family:var(--display);font-weight:700;font-size:clamp(36px,7vw,72px);letter-spacing:-.03em;margin:14px 0 10px;background:linear-gradient(105deg,#54F0A8,#3DE8FF,#9D6BFF);-webkit-background-clip:text;background-clip:text;color:transparent}
.complete-box p{color:var(--muted);max-width:420px;margin:0 auto}
.complete-box .btn{margin-top:30px}

/* assistant */
#bot{position:fixed;left:clamp(12px,2.5vw,28px);bottom:clamp(12px,3vh,26px);z-index:2800;width:96px;opacity:0;cursor:pointer}
#bot svg{display:block;overflow:visible;filter:drop-shadow(0 10px 24px rgba(0,0,0,.5))}
#botBody{animation:bob 3.2s ease-in-out infinite}
@keyframes bob{0%,100%{transform:translateY(0)}50%{transform:translateY(-7px)}}
.eye{animation:blink2 4.6s infinite}
.eye-l{transform-origin:41px 42px}.eye-r{transform-origin:57px 42px}
#antLight{animation:pulse 1.6s infinite}
#bubble{position:fixed;left:clamp(116px,9vw,140px);bottom:clamp(40px,6vh,62px);z-index:2800;
  max-width:250px;padding:13px 16px;border-radius:14px 14px 14px 3px;
  border:1px solid var(--line-hot);background:rgba(6,10,22,.92);backdrop-filter:blur(12px);
  font-size:12.5px;color:var(--text);line-height:1.5;
  box-shadow:0 14px 40px rgba(0,0,0,.55),0 0 24px rgba(61,232,255,.1);
  opacity:0;transform:translateY(8px) scale(.95);transform-origin:bottom left;
  transition:opacity .35s,transform .35s var(--ease);pointer-events:none}
#bubble.show{opacity:1;transform:translateY(0) scale(1)}
#bubble b{color:var(--cyan)}
@media(max-width:700px){#bot{width:70px}#bubble{left:92px;max-width:190px;font-size:11.5px}}

footer{position:relative;z-index:2;border-top:1px solid var(--line);padding:30px clamp(20px,4vw,40px)}
.foot{max-width:1120px;margin:0 auto;display:flex;flex-wrap:wrap;gap:14px;justify-content:space-between;font-family:var(--mono);font-size:11px;letter-spacing:.1em;color:var(--faint)}
.foot .ok{color:var(--green)}
.foot a:hover{color:var(--cyan)}

/* ============================= PHONE (≤560px) ============================= */
@media(max-width:560px){
  body{font-size:15px}
  #introStage{justify-content:flex-start;gap:14px;overflow-y:auto;padding:42px 18px 104px}
  #holoWrap{width:min(190px,58vw)}
  .console{width:100%;padding:16px 18px;border-radius:14px;overflow:hidden}
  .console-tag{align-items:flex-start;font-size:8.5px;letter-spacing:.28em;line-height:1.55}
  #sayPrev{font-size:10px;max-height:52px}
  #sayNow{font-size:15px;line-height:1.5;min-height:2.4em}
  .diag{width:100%;gap:8px;font-size:9.2px;letter-spacing:.08em;padding-bottom:4px}
  .diag .d{display:grid;grid-template-columns:minmax(0,1fr) auto;align-items:center;gap:10px;padding:10px 12px;line-height:1.45}
  .diag .d span{min-width:0;overflow-wrap:anywhere;text-align:left}
  .diag .d b{text-align:right;font-size:9px;letter-spacing:.08em}
  #voiceToggle{left:18px;bottom:16px;padding:10px 14px;font-size:10px;letter-spacing:.12em;z-index:6100}
  #skipIntro{position:fixed;right:18px;bottom:16px;padding:10px 15px;font-size:10px;letter-spacing:.16em;z-index:6100}
  .module{padding:clamp(70px,12vh,104px) 16px}
  .sec-head{margin-bottom:32px}
  .sec-sub{font-size:14.5px}
  /* hero */
  .hero{padding-top:92px}
  .hero h1{font-size:clamp(42px,14vw,72px)}
  .hero-quote{font-size:15px}
  .hero-ctas{margin-top:28px;gap:10px}
  .btn{padding:14px 22px;font-size:12px}
  /* profile / contact */
  .report-id,.report-body{padding:24px 20px}
  .cmd-left,.cmd-form{padding:26px 20px}
  /* skills + experience cards */
  .skillmod{padding:20px}
  .log{margin-left:38px;padding:24px 20px 22px}
  .log:before{left:-30px}.logline{left:12px}
  /* projects */
  .proj{padding:22px}
  .arch{gap:0}
  .arch .node{padding:8px 8px;font-size:9px;line-height:1.35}
  .arch .node small{font-size:7px}
  .arch .wire{min-width:12px}
  .pj-metrics{gap:16px;flex-wrap:wrap}
  /* achievements terminal */
  .term-body{padding:18px}
  .ach-rows{padding:6px 18px 22px;gap:12px}
  .ach{padding:18px 12px}
  /* assistant — keep voice toggle clear of the bot */
  #bubble{max-width:170px}
}

@media (prefers-reduced-motion: reduce){
  *,*:before,*:after{animation-duration:.01ms!important;animation-iteration-count:1!important;transition-duration:.01ms!important}
}
</style>
</head>
<body>

<!-- ============ INTRO ============ -->
<div id="intro" role="dialog" aria-label="AI system boot">
  <div id="enterGate">
    <div class="eg-bg" aria-hidden="true">
      <div class="eg-card c1"><b>KiviCare</b>Healthcare platform<small>Laravel API / MySQL</small></div>
      <div class="eg-card c2"><b>Streamit</b>OTT backend<small>Subscriptions / Catalog</small></div>
      <div class="eg-card c3"><b>Bizinvoice</b>Invoice engine<small>Tax rules / PDF</small></div>
      <div class="eg-card c4"><b>Handyman</b>Service booking<small>REST flows / Status</small></div>
    </div>
    <div class="eg-box">
      <div class="eg-ring" aria-hidden="true"></div>
      <div class="eg-title">{{ strtoupper($settings['name']) }}</div>
      <div class="eg-sub">{{ $settings['footer_brand'] }}</div>
      <p class="eg-copy">Start the portfolio intro. The assistant will briefly introduce the work, skills, and project highlights.</p>
      <button id="enterBtn">START INTRO</button>
      <div class="eg-hint">Voice is optional. Use Skip Intro to open the portfolio directly.</div>
    </div>
  </div>
  <canvas id="warp" aria-hidden="true"></canvas>
  <canvas id="bigName" aria-hidden="true"></canvas>
  <canvas id="introCanvas" aria-hidden="true"></canvas>
  <div class="intro-frame" aria-hidden="true"></div>
  <div id="scanBeam" aria-hidden="true"></div>
  <div id="coreRing" aria-hidden="true"></div>

  <div id="introStage">
    <div class="boot" id="boot" aria-live="polite"></div>
    <div id="holoWrap" aria-hidden="true">
      <svg id="holo" viewBox="0 0 100 116">
        <g opacity=".96">
          <line x1="50" y1="13" x2="50" y2="3" stroke="#3DE8FF" stroke-width="2" opacity=".8"/>
          <circle cx="50" cy="2.4" r="3.2" fill="#3DE8FF"/>
          <circle cx="50" cy="46" r="30" fill="rgba(13,26,52,.55)" stroke="#3DE8FF" stroke-width="1.8"/>
          <ellipse cx="50" cy="44" rx="22" ry="18" fill="rgba(4,12,28,.7)" stroke="#3DE8FF" stroke-width="1.4"/>
          <ellipse cx="42" cy="36" rx="9" ry="5" fill="rgba(120,225,255,.22)"/>
          <g id="holoPupils">
            <circle class="eye2 eye-l" cx="42" cy="44" r="4.6" fill="#3DE8FF"/>
            <circle class="eye2 eye-r" cx="58" cy="44" r="4.6" fill="#3DE8FF"/>
          </g>
          <path id="holoSmile" d="M44 53 Q50 58 56 53" stroke="#3DE8FF" stroke-width="2" fill="none" stroke-linecap="round"/>
          <ellipse id="holoMouth" cx="50" cy="54" rx="5.8" ry="0.6" fill="#3DE8FF" opacity="0"/>
          <g id="holoCheeks" opacity="0">
            <circle cx="36" cy="51" r="2.3" fill="#9D6BFF"/>
            <circle cx="64" cy="51" r="2.3" fill="#9D6BFF"/>
          </g>
          <rect x="34" y="76" width="32" height="26" rx="12" fill="rgba(13,26,52,.55)" stroke="#3DE8FF" stroke-width="1.8"/>
          <rect x="44" y="83" width="12" height="8" rx="3" fill="#9D6BFF" opacity=".9"/>
          <g id="holoArm" style="transform-origin:33px 83px">
            <line x1="34" y1="85" x2="19" y2="74" stroke="#3DE8FF" stroke-width="3.4" stroke-linecap="round" opacity=".85"/>
            <circle cx="18" cy="73" r="4" fill="#3DE8FF"/>
          </g>
          <line x1="66" y1="85" x2="80" y2="92" stroke="#3DE8FF" stroke-width="3.4" stroke-linecap="round" opacity=".85"/>
          <circle cx="81" cy="93" r="4" fill="#9D6BFF"/>
        </g>
      </svg>
      <div class="scanlines"></div>
      <div class="holo-base"></div>
      <div class="talk-burst" id="talkBurst"></div>
      <div class="scan-ring" id="scanRing"></div>
    </div>
    <div class="console" id="console" aria-live="polite">
      <div class="console-tag"><i></i>A.R.I.A. — AI COMMAND CENTER GUIDE <span class="wave"><i></i><i></i><i></i><i></i></span></div>
      <div id="sayPrev"></div>
      <div id="sayNow"></div>
    </div>
    <div class="diag" id="diag" aria-live="polite"></div>
  </div>

  <div id="flash" aria-hidden="true"></div>
  <button id="skipIntro">SKIP INTRO</button>
</div>

<canvas id="net" aria-hidden="true"></canvas>
<div class="gridfloor" aria-hidden="true"></div>
<canvas id="burst" aria-hidden="true"></canvas>
<div class="noise" aria-hidden="true"></div>
<div class="vignette" aria-hidden="true"></div>
<div class="cur-dot" id="curDot" aria-hidden="true"></div>
<div class="cur-ring" id="curRing" aria-hidden="true"></div>

@php
  $wpHrefs = ['#deck','#profile','#skills','#logs','#projects','#achievements','#contact'];
  $wpLabels = preg_split('/\r\n|\r|\n/', trim($settings['nav_waypoints']));
  $sectorNames = array_map('trim', preg_split('/\r\n|\r|\n/', trim($settings['hud_sectors'])));
@endphp
<header class="hud" id="hud">
  <span class="live"><i></i>{{ $settings['hud_status'] }}</span>
  <span class="hide-s">SUBJECT: <span class="name">{{ strtoupper($settings['first_name']) }}&nbsp;{{ strtoupper($settings['last_name']) }}</span></span>
  <span class="sector" id="hudSector">{{ $sectorNames[0] ?? '' }}</span>
  <span class="pbar hide-s"><i id="hudFill"></i></span>
  <span class="pct" id="hudPct">0%</span>
</header>

<nav class="waypoints" id="waypoints" aria-label="Module navigation">
  @foreach($wpHrefs as $i => $href)
  <a class="wp" href="{{ $href }}"><span>{{ trim($wpLabels[$i] ?? '') }}</span></a>
  @endforeach
</nav>

<main>

<!-- MODULE 00 — COMMAND DECK (hero) -->
<section class="module hero" id="deck" aria-label="Command deck">
  <div class="project-backdrop" aria-hidden="true">
    <div class="pb-tile pb1"><b>API Layer</b>Auth / RBAC / REST<br><span>200 OK</span> production flow</div>
    <div class="pb-tile pb2"><b>Database</b>MySQL / MongoDB<br><span>indexed</span> reliable queries</div>
    <div class="pb-tile pb3"><b>Projects</b>KiviCare / Streamit<br><span>live</span> backend systems</div>
    <div class="pb-tile pb4"><b>Support</b>Debug / deploy / deliver<br><span>stable</span> releases</div>
  </div>
  <div class="hero-coord" data-reveal>{{ $settings['hero_coord'] }}</div>
  <h1 aria-label="{{ $settings['name'] }}">
    <span class="row"><span data-hline>{{ strtoupper($settings['first_name']) }}</span></span>
    <span class="row"><span data-hline class="g">{{ strtoupper($settings['last_name']) }}</span></span>
  </h1>
  <div class="hero-role" data-reveal>{{ strtoupper($settings['designation']) }}</div>
  <p class="hero-quote" data-reveal>{!! $settings['tagline'] !!}</p>
  <div class="hero-ctas" data-reveal>
    <a class="btn btn-go" href="#profile" data-magnetic>{{ $settings['hero_cta_primary'] }}</a>
    <a class="btn btn-line" href="#contact" data-magnetic>{{ $settings['hero_cta_secondary'] }}</a>
  </div>
  @foreach(array_slice(array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $settings['hero_chips'])))), 0, 4) as $ci => $chip)
  <div class="holo-chip hc-{{ $ci + 1 }}" data-float>{!! $chip !!}</div>
  @endforeach
  <div class="hero-scroll" aria-hidden="true"><div class="beam"></div>{{ $settings['hero_scroll'] }}</div>
</section>

<!-- MODULE 01 — PROFILE REPORT -->
<section class="module" id="profile" aria-label="AI profile report">
  <div class="wrap">
    <div class="sec-head">
      <div class="sec-tag" data-reveal>{{ $settings['profile_tag'] }}</div>
      <h2 class="sec-title" data-reveal>{!! $settings['profile_title'] !!}<br><span class="g">{!! $settings['profile_title_hl'] !!}</span></h2>
    </div>
    <div class="report glass" data-reveal data-tilt><div class="spot"></div>
      <div class="report-id">
        <div class="shimmer" aria-hidden="true"></div>
        <div class="report-tag">{{ $settings['profile_identity_tag'] }}</div>
        @foreach($profileRows as $r)
        <div class="id-row"><span class="k">{{ $r['k'] }}</span><span class="v {{ $r['cls'] }}">{!! $r['v'] !!}</span></div>
        @endforeach
      </div>
      <div class="report-body">
        <h3>{{ $settings['profile_heading'] }}</h3>
        @foreach($about as $p)
        <p>{!! $p !!}</p>
        @endforeach
        <div class="report-note">{!! $settings['profile_note'] !!}</div>
      </div>
    </div>
  </div>
</section>

<!-- MODULE 02 — SKILL MODULES -->
<section class="module" id="skills" aria-label="Skill modules">
  <div class="wrap">
    <div class="sec-head" style="text-align:center">
      <div class="sec-tag" style="justify-content:center" data-reveal>{{ $settings['skills_tag'] }}</div>
      <h2 class="sec-title" data-reveal>{!! $settings['skills_title'] !!}<br><span class="g">{!! $settings['skills_title_hl'] !!}</span></h2>
      <p class="sec-sub" style="margin-left:auto;margin-right:auto" data-reveal>{{ $settings['skills_sub'] }}</p>
    </div>
    <div class="modgrid-wrap">
      <canvas id="neural" aria-hidden="true"></canvas>
      <div class="modgrid">
        @foreach($skillCategories as $cat)
        <article class="glass skillmod {{ $cat->wide ? 'wide' : '' }}" data-reveal data-tilt data-node><div class="spot"></div><i class="corner c-tl"></i><i class="corner c-br"></i>
          <div class="mod-head"><h3><span class="ic">{{ $cat->icon }}</span>{{ $cat->name }}</h3><span class="mod-status"><i></i>ACTIVE</span></div>
          <div class="mod-list">
            @foreach($cat->skills as $s)
            <div class="mod-row"><div class="top"><span>{{ $s->name }}</span><em data-en>0%</em></div><div class="ebar"><i data-bar="{{ $s->level }}"></i></div></div>
            @endforeach
          </div>
        </article>
        @endforeach
      </div>
    </div>
  </div>
</section>

<!-- MODULE 03 — EXPERIENCE DATABASE -->
<section class="module" id="logs" aria-label="Experience database">
  <div class="wrap">
    <div class="sec-head">
      <div class="sec-tag" data-reveal>{{ $settings['exp_tag'] }}</div>
      <h2 class="sec-title" data-reveal>{!! $settings['exp_title'] !!}<br><span class="g">{!! $settings['exp_title_hl'] !!}</span></h2>
    </div>
    <div class="logs">
      <div class="logline" aria-hidden="true"><i id="logFill"></i></div>
      @foreach($experiences as $xp)
      <article class="glass log" data-reveal data-tilt><div class="spot"></div><i class="corner c-tl"></i><i class="corner c-br"></i>
        <div class="log-id" @if($xp->live) style="color:var(--violet)" @endif>MISSION LOG {{ sprintf('%02d', $loop->iteration) }}
          <span class="tx"><i></i>{{ $xp->live ? 'LIVE TRANSMISSION' : 'TRANSMISSION ARCHIVED' }}</span>
        </div>
        <h3>{{ $xp->company }}</h3>
        @if($xp->sub)<div class="org-sub">{{ $xp->sub }}</div>@endif
        <span class="log-when">{{ $xp->period }}</span>
        <div class="log-role">{{ $xp->role }}</div>
        <div class="resp">
          @foreach($xp->responsibilities ?? [] as $r)<span class="tag">{{ $r }}</span>@endforeach
        </div>
      </article>
      @endforeach
    </div>
  </div>
</section>

<!-- MODULE 04 — PROJECT COMMAND CENTER -->
<section class="module" id="projects" aria-label="Project command center">
  <div class="wrap">
    <div class="sec-head">
      <div class="sec-tag" data-reveal>{{ $settings['projects_tag'] }}</div>
      <h2 class="sec-title" data-reveal>{!! $settings['projects_title'] !!}<br><span class="g">{!! $settings['projects_title_hl'] !!}</span></h2>
      <p class="sec-sub" data-reveal>{{ $settings['projects_sub'] }}</p>
    </div>
    <div class="projects">
      @foreach($projects as $p)
      <article class="glass proj pj-{{ min($loop->iteration, 5) }}" @if($p->wide) style="grid-column:span 4" @endif data-reveal data-tilt><div class="spot"></div><i class="corner c-tl"></i><i class="corner c-br"></i>
        <div class="pj-kicker"><i style="background:{{ $p->color }};box-shadow:0 0 10px {{ $p->color }}"></i>PROJECT {{ sprintf('%02d', $loop->iteration) }} · {{ strtoupper($p->kicker) }}</div>
        <h3>{{ $p->title }}</h3>
        <p>{{ $p->description }}</p>
        @if($p->arch)
        <div class="arch" aria-label="Architecture diagram">
          @foreach($p->arch as $node)
          <span class="node" @if($node['hot'] ?? false) style="border-color:{{ $p->color }}" @endif>{{ $node['label'] }}@if(!empty($node['sub']))<small>{{ $node['sub'] }}</small>@endif</span>
          @if(!$loop->last)<span class="wire"><i></i><i></i></span>@endif
          @endforeach
        </div>
        @endif
        @if($p->metrics)
        <div class="pj-metrics">
          @foreach($p->metrics as $m)<div><b>{{ $m['value'] }}</b><span>{{ $m['label'] }}</span></div>@endforeach
        </div>
        @endif
        <div class="pj-tags">
          @foreach($p->tags ?? [] as $t)<span class="tag">{{ $t }}</span>@endforeach
        </div>
      </article>
      @endforeach
    </div>
  </div>
</section>

<!-- MODULE 05 — ACHIEVEMENT TERMINAL -->
<section class="module" id="achievements" aria-label="Achievement terminal">
  <div class="wrap">
    <div class="sec-head">
      <div class="sec-tag" data-reveal>{{ $settings['ach_tag'] }}</div>
      <h2 class="sec-title" data-reveal>{!! $settings['ach_title'] !!}<br><span class="g">{!! $settings['ach_title_hl'] !!}</span></h2>
    </div>
    <div class="terminal glass" data-reveal><div class="spot"></div>
      <div class="term-bar"><i></i><i></i><i></i><span>aria@command-center: ~/achievements</span></div>
      <div class="term-body">
        <span class="q">aria></span> SELECT * FROM achievements WHERE subject = '{{ strtolower(str_replace(' ', '_', $settings['name'])) }}';<br>
        <span class="dim">→ {{ $achievements->count() }} rows returned in 0.002s · all records verified ✓</span>
      </div>
      <div class="ach-rows">
        @foreach($achievements as $a)
        <div class="ach" data-ach>
          <div class="num">@if($a->count !== null)<span data-count="{{ $a->count }}">0</span>{{ $a->suffix }}@else{{ $a->value }}@endif</div>
          <div class="lab">{{ strtoupper($a->label) }}</div>
          <div class="st">★ VERIFIED</div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</section>

<!-- MODULE 06 — CONTACT -->
<section class="module" id="contact" aria-label="Contact command center" style="padding-bottom:140px">
  <div class="wrap">
    <div class="sec-head">
      <div class="sec-tag" data-reveal>{{ $settings['contact_tag'] }}</div>
      <h2 class="sec-title" data-reveal>{!! $settings['contact_title'] !!}<br><span class="g">{!! $settings['contact_title_hl'] !!}</span></h2>
    </div>
    <div class="command" data-reveal>
      <div class="cmd-left">
        <h3>{{ $settings['contact_heading'] }}</h3>
        <p>{{ $settings['contact_text'] }}</p>
        <div class="ai-line">A.R.I.A.: <b>"{{ $settings['contact_ai_1'] }}"</b><br>"{{ $settings['contact_ai_2'] }}"</div>
        <div class="cmd-stats">
          <div class="cmd-stat"><b>RESPONSE TIME</b>{{ $settings['response_time'] }}</div>
          <div class="cmd-stat"><b>TIMEZONE</b>{{ $settings['timezone'] }}</div>
          <div class="cmd-stat"><b>STATUS</b>{{ strtoupper($settings['status_label']) }}</div>
          <div class="cmd-stat"><b>BASE</b>{{ strtoupper($settings['location']) }}</div>
        </div>
        <div class="cmd-links">
          <a class="cmd-link" href="mailto:{{ $settings['email'] }}">
            <span class="ic"><svg viewBox="0 0 24 24"><path d="M2 4h20a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1zm10 8.5L3.5 6v12h17V6L12 12.5z"/></svg></span>
            {{ $settings['email'] }}
          </a>
          <a class="cmd-link" href="{{ $settings['linkedin'] }}" target="_blank" rel="noopener">
            <span class="ic"><svg viewBox="0 0 24 24"><path d="M4.98 3.5C4.98 4.88 3.87 6 2.5 6S0 4.88 0 3.5 1.12 1 2.5 1 4.98 2.12 4.98 3.5zM.2 8h4.6v14H.2V8zm7.5 0h4.4v1.9h.1c.6-1.1 2.1-2.3 4.3-2.3 4.6 0 5.5 3 5.5 7V22h-4.6v-6.6c0-1.6 0-3.6-2.2-3.6s-2.6 1.7-2.6 3.5V22H7.7V8z"/></svg></span>
            {{ $settings['linkedin_label'] }}
          </a>
          <a class="cmd-link" href="{{ route('resume.download') }}" id="resumeBtn">
            <span class="ic"><svg viewBox="0 0 24 24"><path d="M12 3v10.6l3.3-3.3 1.4 1.4L12 17.4l-4.7-4.7 1.4-1.4 3.3 3.3V3h2zM4 19h16v2H4v-2z"/></svg></span>
            {{ $settings['resume_label'] }}
          </a>
        </div>
      </div>
      <form class="cmd-form" id="cmdForm" novalidate>
        <div class="fld"><input id="cName" name="name" type="text" placeholder=" " autocomplete="name" required><label for="cName">{{ $settings['contact_label_name'] }}</label></div>
        <div class="fld"><input id="cEmail" name="email" type="email" placeholder=" " autocomplete="email" required><label for="cEmail">{{ $settings['contact_label_email'] }}</label></div>
        <div class="fld"><textarea id="cMsg" name="message" placeholder=" " required></textarea><label for="cMsg">{{ $settings['contact_label_message'] }}</label></div>
        <button class="btn launch" type="submit" data-magnetic>
          <span>{{ $settings['contact_btn'] }}</span>
          <span class="ok2">{{ $settings['contact_btn_sent'] }}</span>
        </button>
      </form>
    </div>
  </div>
</section>
</main>

<div id="complete" role="dialog" aria-label="Connection established">
  <div class="complete-box">
    <div class="t">{{ $settings['complete_tag'] }}</div>
    <h2>{{ $settings['complete_title'] }}</h2>
    <p>{{ $settings['complete_text'] }}</p>
    <button class="btn btn-line" id="completeClose" data-magnetic>{{ $settings['complete_btn'] }}</button>
  </div>
</div>

<div id="bot" aria-label="AI assistant A.R.I.A." role="img">
  <svg viewBox="0 0 100 110">
    <g id="botBody">
      <line x1="50" y1="14" x2="50" y2="4" stroke="#8C97B4" stroke-width="2"/>
      <circle id="antLight" cx="50" cy="3" r="3.4" fill="#3DE8FF"/>
      <circle cx="50" cy="46" r="30" fill="#0D1428" stroke="#3A4A75" stroke-width="2"/>
      <ellipse cx="50" cy="44" rx="22" ry="18" fill="#071020" stroke="#3DE8FF" stroke-width="1.6" opacity=".95"/>
      <ellipse cx="42" cy="36" rx="9" ry="5" fill="rgba(120,200,255,.18)"/>
      <g id="pupils">
        <circle class="eye eye-l" cx="42" cy="44" r="4.6" fill="#3DE8FF"/>
        <circle class="eye eye-r" cx="58" cy="44" r="4.6" fill="#3DE8FF"/>
      </g>
      <path id="botSmile" d="M44 53 Q50 58 56 53" stroke="#3DE8FF" stroke-width="2" fill="none" stroke-linecap="round"/>
      <ellipse id="botMouth" cx="50" cy="54" rx="5.5" ry="0.5" fill="#3DE8FF" opacity="0"/>
      <rect x="34" y="74" width="32" height="24" rx="11" fill="#0D1428" stroke="#3A4A75" stroke-width="2"/>
      <rect x="44" y="80" width="12" height="8" rx="3" fill="#9D6BFF" opacity=".85"/>
      <g id="armWave" style="transform-origin:33px 80px">
        <line x1="34" y1="82" x2="20" y2="72" stroke="#3A4A75" stroke-width="4" stroke-linecap="round"/>
        <circle cx="19" cy="71" r="4" fill="#3DE8FF"/>
      </g>
      <line x1="66" y1="82" x2="79" y2="88" stroke="#3A4A75" stroke-width="4" stroke-linecap="round"/>
      <circle cx="80" cy="89" r="4" fill="#9D6BFF"/>
    </g>
  </svg>
</div>
<div id="bubble"></div>
<button id="voiceToggle" aria-pressed="true" title="Toggle A.R.I.A. voice"><span id="voiceIco">🔊</span> <span id="voiceTxt">VOICE</span></button>

<footer>
  <div class="foot">
    <span>© {{ date('Y') }} {{ strtoupper($settings['name']) }} · {{ $settings['footer_brand'] }}</span>
    <span><span class="ok">●</span> {{ $settings['footer_status'] }}</span>
    <a href="#deck">{{ $settings['footer_back'] }}</a>
  </div>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/lenis@1.1.14/dist/lenis.min.js"></script>
<script>
(function(){
"use strict";
const SUBJ_FIRST=@json(strtoupper($settings['first_name']));
const SUBJ_LAST=@json(strtoupper($settings['last_name']));
const SUBJ_FULL=SUBJ_FIRST+" "+SUBJ_LAST;
const SUBJ_ROLE=@json(strtoupper($settings['designation'] ?? ''));
const CONTACT_URL=@json(route('contact.store'));
const reduced = matchMedia('(prefers-reduced-motion: reduce)').matches;
const fine = matchMedia('(hover:hover) and (pointer:fine)').matches;
const hasGSAP = typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined';
if (hasGSAP) gsap.registerPlugin(ScrollTrigger);
const isMobile = innerWidth < 760;

/* =================== AMBIENT NEURAL NETWORK BG =================== */
const netCv = document.getElementById('net'), nx = netCv.getContext('2d');
let NW, NH, pts = [], mouse = {x:-9999,y:-9999};
function netSize(){ NW = netCv.width = innerWidth; NH = netCv.height = innerHeight; }
netSize(); addEventListener('resize', netSize);
const PN = isMobile ? 36 : 72;
for (let i=0;i<PN;i++) pts.push({x:Math.random()*innerWidth,y:Math.random()*innerHeight,vx:(Math.random()-.5)*.32,vy:(Math.random()-.5)*.32,r:Math.random()*1.5+.5});
addEventListener('pointermove', e=>{ mouse.x=e.clientX; mouse.y=e.clientY; }, {passive:true});
(function netFrame(){
  nx.clearRect(0,0,NW,NH);
  for (const p of pts){
    p.x+=p.vx; p.y+=p.vy;
    if (p.x<0||p.x>NW) p.vx*=-1; if (p.y<0||p.y>NH) p.vy*=-1;
    nx.beginPath(); nx.arc(p.x,p.y,p.r,0,7);
    nx.fillStyle='rgba(140,160,210,.4)'; nx.fill();
  }
  for (let i=0;i<PN;i++) for (let j=i+1;j<PN;j++){
    const a=pts[i],b=pts[j],dx=a.x-b.x,dy=a.y-b.y,d=dx*dx+dy*dy;
    if (d<15600){ nx.strokeStyle=`rgba(77,124,254,${.13*(1-d/15600)})`; nx.lineWidth=1;
      nx.beginPath(); nx.moveTo(a.x,a.y); nx.lineTo(b.x,b.y); nx.stroke(); }
  }
  for (const p of pts){
    const dx=p.x-mouse.x,dy=p.y-mouse.y,d=dx*dx+dy*dy;
    if (d<30000){ nx.strokeStyle=`rgba(61,232,255,${.28*(1-d/30000)})`;
      nx.beginPath(); nx.moveTo(p.x,p.y); nx.lineTo(mouse.x,mouse.y); nx.stroke(); }
  }
  if (!reduced) requestAnimationFrame(netFrame);
})();

/* =================== BURSTS =================== */
const burstCv=document.getElementById('burst'), bx2=burstCv.getContext('2d');
let BW,BH,bursts=[];
function burstSize(){BW=burstCv.width=innerWidth;BH=burstCv.height=innerHeight;}
burstSize(); addEventListener('resize',burstSize);
function burstAt(x,y,colors,n){ if (reduced) return;
  for (let i=0;i<(n||34);i++){const a=Math.random()*6.28,v=2+Math.random()*5;
    bursts.push({x,y,vx:Math.cos(a)*v,vy:Math.sin(a)*v-2,life:1,r:Math.random()*2.6+1,c:colors[i%colors.length]});} }
(function burstFrame(){
  bx2.clearRect(0,0,BW,BH);
  for (let i=bursts.length-1;i>=0;i--){const p=bursts[i];
    p.x+=p.vx;p.y+=p.vy;p.vy+=.12;p.life-=.018;
    if (p.life<=0){bursts.splice(i,1);continue;}
    bx2.globalAlpha=Math.max(p.life,0);bx2.fillStyle=p.c;
    bx2.beginPath();bx2.arc(p.x,p.y,p.r,0,7);bx2.fill();}
  bx2.globalAlpha=1;requestAnimationFrame(burstFrame);
})();

/* ======================================================================
   INTRO — AI SYSTEM BOOT
====================================================================== */
const intro=document.getElementById('intro');
const bootEl=document.getElementById('boot');
const holoWrap=document.getElementById('holoWrap');
const consoleEl=document.getElementById('console');
const sayPrev=document.getElementById('sayPrev');
const sayNow=document.getElementById('sayNow');
const diagEl=document.getElementById('diag');
const introCv=document.getElementById('introCanvas');
const nameCv=document.getElementById('bigName');
const warpCv=document.getElementById('warp');
let introDone=false, introTimers=[];
function later(fn,ms){const t=setTimeout(()=>{if(!introDone)fn();},ms);introTimers.push(t);return t;}

/* ---------- ROBOT SOUND (Web Audio synth) ----------
   Synthesised sci-fi chatter that plays UNDER the spoken line. Unlike the
   speech engine — which Chrome silently drops when invoked a few seconds
   after the last gesture, and which is mute on machines with no TTS voice —
   Web Audio keeps working once unlocked, so the bot is always audibly "alive". */
const RobotSfx=(function(){
  let ctx=null,master=null,chatterTimer=null,currentGen=0;
  function ensure(){
    if(ctx)return ctx;
    try{
      const AC=window.AudioContext||window.webkitAudioContext;
      if(!AC)return null;
      ctx=new AC();
      master=ctx.createGain();master.gain.value=.9;master.connect(ctx.destination);
    }catch(e){ctx=null;}
    return ctx;
  }
  function unlock(){ensure();if(ctx&&ctx.state==='suspended')ctx.resume().catch(()=>{});}
  // one short robotic blip ("phoneme") starting at time t
  function blip(t,freq,dur){
    const o=ctx.createOscillator(),g=ctx.createGain(),f=ctx.createBiquadFilter();
    o.type=Math.random()<.5?'square':'sawtooth';
    o.frequency.setValueAtTime(freq,t);
    o.frequency.linearRampToValueAtTime(freq*(.8+Math.random()*.5),t+dur);
    f.type='bandpass';f.frequency.value=freq*2.2;f.Q.value=7;
    g.gain.setValueAtTime(0,t);
    g.gain.linearRampToValueAtTime(.05,t+.012);
    g.gain.exponentialRampToValueAtTime(.0005,t+dur);
    o.connect(f);f.connect(g);g.connect(master);
    o.start(t);o.stop(t+dur+.03);
  }
  // deep, male-leaning robot range
  const BANK=[150,138,168,128,180,144];
  function startChatter(){
    if(!ensure())return null;
    if(ctx.state==='suspended')ctx.resume().catch(()=>{});
    if(chatterTimer){clearTimeout(chatterTimer);chatterTimer=null;}
    const my=++currentGen;
    (function loop(){
      if(my!==currentGen)return;            // superseded by a newer line
      const now=ctx.currentTime,n=2+Math.floor(Math.random()*3);
      let t=now;
      for(let i=0;i<n;i++){
        const fr=BANK[Math.floor(Math.random()*BANK.length)]*(.9+Math.random()*.45);
        const d=.05+Math.random()*.06;
        blip(t,fr,d);t+=d+.02+Math.random()*.03;
      }
      chatterTimer=setTimeout(loop,(t-now)*1000+110+Math.random()*170);
    })();
    return my;
  }
  function stopChatter(gen){
    if(gen!=null&&gen!==currentGen)return;   // stale stop from a previous line — ignore
    currentGen++;                            // halts any running loop on its next tick
    if(chatterTimer){clearTimeout(chatterTimer);chatterTimer=null;}
  }
  return {unlock,startChatter,stopChatter};
})();

/* ---------- meSpeak — eSpeak compiled to JS: a TRUE robotic voice that
   runs 100% in the browser (no server/API), works on dynamic text, and
   sounds the same on every machine (unlike the OS Web-Speech voices). ---------- */
const MESPEAK_BASE='https://cdn.jsdelivr.net/npm/mespeak@1.9.6/';   // browser-global build (exposes window.meSpeak)
let meSpeakReady=false;
(function loadMeSpeak(){
  const s=document.createElement('script');
  s.src=MESPEAK_BASE+'mespeak.min.js';
  s.onload=function(){
    try{
      meSpeak.loadConfig(MESPEAK_BASE+'mespeak_config.json');
      meSpeak.loadVoice(MESPEAK_BASE+'voices/en/en-us.json',function(ok){meSpeakReady=!!ok;});
    }catch(e){meSpeakReady=false;}
  };
  s.onerror=function(){meSpeakReady=false;};   // CDN blocked → fall back to Web Speech API
  document.head.appendChild(s);
})();
function meSpeakAvailable(){return meSpeakReady&&typeof window.meSpeak!=='undefined'&&meSpeak.isVoiceLoaded&&meSpeak.isVoiceLoaded();}
// Prime meSpeak's audio inside a user gesture so it can play later (Chrome autoplay).
function meSpeakPrime(){if(meSpeakAvailable()){try{meSpeak.speak(' ',{amplitude:0});}catch(e){}}}

/* ---------- VOICE NARRATION (Web Speech API) ---------- */
const canSpeak=('speechSynthesis' in window);
const VOICE_LANG=@json($settings['intro_voice_lang'] ?: 'en-US');
const VOICE_GENDER=@json(strtolower($settings['intro_voice_gender'] ?: 'male'));
const VOICE_NAME=@json($settings['intro_voice_name'] ?? '');
// voice is wanted purely from the setting — meSpeak can speak even when the OS has no Web-Speech voice
let voiceOn={{ ($settings['intro_voice'] ?? '1') !== '0' && $settings['intro_voice'] !== '' ? 'true' : 'false' }};
let pickedVoice=null;
// Heuristic gender match by voice name (the API doesn't expose gender directly).
const MALE_RE=/\b(male|david|mark|guy|george|daniel|alex|fred|james|john|paul|ravi|rishi|prabhat|hemant|man)\b/i;
const FEMALE_RE=/\b(female|zira|susan|hazel|samantha|victoria|karen|moira|tessa|fiona|heera|kalpana|swara|woman|girl)\b/i;
function genderOf(v){
  const n=(v.name||'');
  if(FEMALE_RE.test(n))return 'female';
  if(MALE_RE.test(n))return 'male';
  return 'any';
}
function loadVoice(){
  if(!canSpeak)return;
  const vs=speechSynthesis.getVoices();
  if(!vs.length){pickedVoice=null;return;}
  const lc=VOICE_LANG.toLowerCase(), base=lc.split('-')[0];
  // 1) exact name wins
  if(VOICE_NAME){
    const byName=vs.find(v=>v.name&&v.name.toLowerCase().includes(VOICE_NAME.toLowerCase()));
    if(byName){pickedVoice=byName;return;}
  }
  const exact=vs.filter(v=>v.lang&&v.lang.toLowerCase()===lc);
  const baseM=vs.filter(v=>v.lang&&v.lang.toLowerCase().startsWith(base));
  const enM=vs.filter(v=>/^en/i.test(v.lang));
  // 2) try gender within progressively wider language pools
  if(VOICE_GENDER==='male'||VOICE_GENDER==='female'){
    for(const pool of [exact,baseM,enM,vs]){
      const m=pool.find(v=>genderOf(v)===VOICE_GENDER);
      if(m){pickedVoice=m;return;}
    }
  }
  // 3) fall back to best language match
  pickedVoice=exact[0]||baseM[0]||enM[0]||vs[0]||null;
}
if(canSpeak){loadVoice();speechSynthesis.onvoiceschanged=loadVoice;}
const vBtn=document.getElementById('voiceToggle');
function paintVoiceBtn(){
  if(!vBtn)return;
  vBtn.classList.toggle('off',!voiceOn);
  vBtn.setAttribute('aria-pressed',String(voiceOn));
  document.getElementById('voiceIco').textContent=voiceOn?'🔊':'🔇';
  document.getElementById('voiceTxt').textContent=voiceOn?'VOICE ON':'VOICE OFF';
}
function speak(text,opts){
  opts=opts||{};
  if(!voiceOn||!text){opts.onend&&opts.onend();return;}
  // ---- preferred path: the device's own system voice (Siri on iOS, Google TTS on Android, etc.) ----
  if(canSpeak){
    if(!pickedVoice)loadVoice();
    if(pickedVoice){
      try{
        if(speechSynthesis.speaking||speechSynthesis.pending)speechSynthesis.cancel();
        if(speechSynthesis.paused){try{speechSynthesis.resume();}catch(e){}}
        const u=new SpeechSynthesisUtterance(text);
        u.voice=pickedVoice;
        u.lang=pickedVoice.lang||VOICE_LANG;
        const malePitch=(VOICE_GENDER==='male'), basePitch=malePitch?0.8:1.05;
        if(opts.bot||opts.intro){u.rate=1.02;u.pitch=malePitch?0.85:1.6;}   // male keeps it deep; female stays chirpy
        else{u.rate=1;u.pitch=basePitch;}
        u.volume=1;
        // robotic sound layer — start NOW (don't wait for onstart, which Chrome may skip)
        const chatter=RobotSfx.startChatter();
        const stopBackstop=setTimeout(()=>RobotSfx.stopChatter(chatter),Math.min(15000,text.length*120+2500));
        u.onstart=()=>vBtn&&vBtn.classList.add('speaking');
        u.onend=u.onerror=()=>{clearTimeout(stopBackstop);RobotSfx.stopChatter(chatter);vBtn&&vBtn.classList.remove('speaking');opts.onend&&opts.onend();};
        speechSynthesis.speak(u);
        return;
      }catch(e){/* fall through to meSpeak */}
    }
  }
  // ---- fallback: meSpeak (robotic eSpeak voice) — only when the OS has no Web-Speech voice at all ----
  if(meSpeakAvailable()){
    try{
      meSpeak.stop();                                   // cut off any line still talking
      if(canSpeak){try{speechSynthesis.cancel();}catch(e){}}
      const male=(VOICE_GENDER!=='female');
      // low pitch + slightly slow = that classic deep robot delivery
      const o={amplitude:100,volume:1,pitch:male?22:60,speed:165,wordgap:1};
      vBtn&&vBtn.classList.add('speaking');
      let done=false;
      const finish=()=>{if(done)return;done=true;vBtn&&vBtn.classList.remove('speaking');opts.onend&&opts.onend();};
      meSpeak.speak(text,o,function(){finish();});       // callback fires when playback ends
      return;
    }catch(e){/* fall through to chatter-only */}
  }
  // neither a system voice nor meSpeak — keep the robot audible with chatter
  const c=RobotSfx.startChatter();
  setTimeout(()=>{RobotSfx.stopChatter(c);opts.onend&&opts.onend();},Math.min(9000,text.length*70+700));
}
/* ---- robot talking mouth (visual, runs whenever the bot says something) ---- */
let botMouthTl=null;
function botMouthStart(){
  const m=document.getElementById('botMouth'),s=document.getElementById('botSmile');
  if(!m)return;
  if(botMouthTl){botMouthTl.kill();botMouthTl=null;}
  if(hasGSAP&&!reduced){
    gsap.to(s,{opacity:0,duration:.12});
    gsap.set(m,{opacity:1});
    botMouthTl=gsap.timeline({repeat:-1,defaults:{ease:'sine.inOut'}});
    botMouthTl.to(m,{attr:{ry:4.4},duration:.11})
              .to(m,{attr:{ry:1},duration:.11})
              .to(m,{attr:{ry:3.2},duration:.09})
              .to(m,{attr:{ry:1.4},duration:.12})
              .to(m,{attr:{ry:3.8},duration:.1})
              .to(m,{attr:{ry:1},duration:.13});
  }else{m.setAttribute('opacity','1');}
}
function botMouthStop(){
  const m=document.getElementById('botMouth'),s=document.getElementById('botSmile');
  if(botMouthTl){botMouthTl.kill();botMouthTl=null;}
  if(!m)return;
  if(hasGSAP&&!reduced){gsap.to(m,{opacity:0,attr:{ry:0.5},duration:.15});gsap.to(s,{opacity:1,duration:.25,delay:.05});}
  else{m.setAttribute('opacity','0');if(s)s.setAttribute('opacity','1');}
}
/* ---- intro hologram "talking" action (pulses while A.R.I.A. narrates) ---- */
let holoTalkTl=null;
function holoTalkStart(){
  if(!hasGSAP||reduced)return;
  holoTalkStop();
  gsap.set('#holoMouth',{opacity:1,attr:{rx:5.8,ry:.8}});
  gsap.to('#holoSmile',{opacity:0,duration:.08});
  gsap.to('#holoCheeks',{opacity:.5,duration:.12});
  gsap.to('#talkBurst',{opacity:.55,scale:1.06,duration:.35,yoyo:true,repeat:-1,ease:'sine.inOut'});
  gsap.to('#holoArm',{rotate:-20,duration:.28,yoyo:true,repeat:-1,ease:'sine.inOut',transformOrigin:'33px 83px'});
  gsap.to('#holoPupils circle',{attr:{r:5.4},duration:.18,yoyo:true,repeat:-1,ease:'sine.inOut'});
  gsap.to('#holo circle:first-of-type',{attr:{r:4.4},duration:.22,yoyo:true,repeat:-1,ease:'sine.inOut'});
  holoTalkTl=gsap.timeline({repeat:-1,defaults:{ease:'sine.inOut'}});
  holoTalkTl.to('#holo',{scale:1.03,duration:.12,transformOrigin:'50% 50%'},0)
            .to('#holoMouth',{attr:{rx:7.2,ry:4.6},duration:.09},0)
            .to('#holoMouth',{attr:{rx:4.8,ry:1.2},duration:.08})
            .to('#holo',{scale:1.01,duration:.08},'<')
            .to('#holoMouth',{attr:{rx:6.4,ry:3.4},duration:.1})
            .to('#holoMouth',{attr:{rx:5.2,ry:.9},duration:.09})
            .to('#holo',{scale:1.025,duration:.1},'<');
}
function holoTalkStop(){
  if(holoTalkTl){holoTalkTl.kill();holoTalkTl=null;}
  if(hasGSAP&&!reduced){
    gsap.killTweensOf(['#holoPupils circle','#holoMouth','#holoSmile','#holoCheeks','#talkBurst','#holoArm','#holo circle:first-of-type']);
    gsap.to('#holo',{scale:1,duration:.25,transformOrigin:'50% 50%'});
    gsap.to('#holoPupils circle',{attr:{r:4.6},duration:.25});
    gsap.to('#holoMouth',{opacity:0,attr:{rx:5.8,ry:.6},duration:.12});
    gsap.to('#holoSmile',{opacity:1,duration:.18});
    gsap.to('#holoCheeks',{opacity:0,duration:.18});
    gsap.to('#talkBurst',{opacity:0,scale:.74,duration:.18});
    gsap.to('#holoArm',{rotate:0,duration:.25,transformOrigin:'33px 83px'});
    gsap.to('#holo circle:first-of-type',{attr:{r:3.2},duration:.2});
  }
}
function stopSpeak(){if(meSpeakAvailable()){try{meSpeak.stop();}catch(e){}}if(canSpeak){try{speechSynthesis.cancel();}catch(e){}}RobotSfx.stopChatter();holoTalkStop();vBtn&&vBtn.classList.remove('speaking');}
/* Browsers block speech until the FIRST user gesture. A plain resume() isn't
   enough — we must call speak() inside a gesture once. So on the first tap /
   click / key we fire a silent utterance to unlock the engine; after that the
   bot speaks on its own (scroll, idle, etc.) with no need to toggle. */
if(canSpeak){
  let audioPrimed=false;
  const primeAudio=()=>{
    if(audioPrimed)return; audioPrimed=true;
    RobotSfx.unlock();meSpeakPrime();
    try{const u=new SpeechSynthesisUtterance(' ');u.volume=0;speechSynthesis.speak(u);}catch(e){}
  };
  ['pointerdown','keydown','touchstart','click'].forEach(ev=>addEventListener(ev,primeAudio,{passive:true}));
  // Chrome pauses long-idle speech queues — nudge it awake on scroll.
  addEventListener('scroll',()=>{try{if(speechSynthesis.paused)speechSynthesis.resume();}catch(e){}},{passive:true});
}
if(vBtn){
  // keep the toggle — meSpeak (robot voice) works even when the OS has no Web-Speech voice
  paintVoiceBtn();
  vBtn.addEventListener('click',()=>{
    voiceOn=!voiceOn;paintVoiceBtn();
    if(!voiceOn){stopSpeak();botMouthStop();}
    // Turning on: re-say the bot's current bubble (NOT the silent intro).
    else if(botVisible&&bubble.classList.contains('show')&&bubble.textContent.trim()){
      speak(bubble.textContent.trim(),{bot:true});botMouthStart();
    }
  });
}

@php
  /* ---------- INTRO CONTENT (editable from Admin → Settings) ---------- */
  $expCount  = $experiences->count();
  $projCount = $projects->count();

  // Boot sequence — record counts are pulled live from the database.
  $bootLines = [
    ['STARTING PORTFOLIO', '<em>OK</em>'],
    ['LOADING PROFILE SUMMARY', '<em>READY</em>'],
    ['CHECKING BACKEND SKILLS', '<em>READY</em>'],
    ['LOADING EXPERIENCE', '<em>' . $expCount . ' ' . \Illuminate\Support\Str::plural('RECORD', $expCount) . '</em>'],
    ['CHECKING PROJECTS', '<em>' . $projCount . ' READY</em>'],
    ['OPENING INTRO', '<em>DONE</em>'],
  ];

  // Spoken narration lines — fall back to defaults when the setting is empty.
  $greetings = array_values(array_filter(array_map('trim', [
    $settings['intro_greeting_1'] ?? 'Hello, Visitor.',
    $settings['intro_greeting_2'] ?? 'Welcome to the portfolio.',
  ])));
  $introGuide     = $settings['intro_guide']     ?? 'I will guide you through the highlights.';
  $introPitch     = $settings['intro_pitch']     ?? 'This portfolio presents backend experience, technical skills, production projects, and ways to connect.';
  $introValidated = $settings['intro_validated'] ?? 'Profile overview ready.';
  $introWelcome   = $settings['intro_welcome']   ?? 'Opening portfolio.';

  // Diagnostics — one per line, format "LABEL | RESULT". Put a ★ in the result for the gold highlight.
  $diagDefault =
      "BACKEND ENGINEERING EXPERTISE | DETECTED ✓\n" .
      "API DEVELOPMENT EXPERTISE | DETECTED ✓\n" .
      "SYSTEM ARCHITECTURE KNOWLEDGE | CONFIRMED ✓\n" .
      "PROJECT DELIVERY EXPERIENCE | VERIFIED ✓\n" .
      "PROBLEM SOLVING CAPABILITIES | CONFIRMED ✓\n" .
      "PROFESSIONAL RELIABILITY SCORE | EXCEPTIONAL ★";
  $diagLines = preg_split('/\r\n|\r|\n/', $settings['intro_diagnostics'] ?? $diagDefault);

  // Assemble the narration script consumed by phaseDialogue().
  $introScript = [];
  foreach ($greetings as $g) {
    $introScript[] = ['t' => $g, 'd' => 1450];
  }
  $introScript[] = ['t' => $introGuide, 'wave' => true, 'd' => 1450];
  $introScript[] = ['t' => $introPitch, 'd' => 2100];
  $introScript[] = ['t' => 'Scanning profile highlights...', 'scan' => true, 'd' => 2300];
  foreach ($diagLines as $line) {
    if (trim($line) === '') continue;
    $parts  = explode('|', $line, 2);
    $label  = strtoupper(trim($parts[0]));
    $result = trim($parts[1] ?? 'CONFIRMED ✓');
    $introScript[] = str_contains($result, '★')
      ? ['diag' => $label, 'ok' => $result, 'exc' => true, 'd' => 1000]
      : ['diag' => $label, 'ok' => $result, 'd' => 680];
  }
  $introScript[] = ['t' => $introValidated, 'd' => 1500];
  $introScript[] = ['t' => 'Preparing portfolio...', 'd' => 1500];
  $introScript[] = ['t' => $introWelcome, 'd' => 1200, 'launch' => true];
@endphp
const bootLines=@json($bootLines);
function phaseBoot(){
  bootLines.forEach((l,idx)=>{
    const ln=document.createElement('span');ln.className='ln'+(idx===0||idx===5?' sys':'');
    ln.innerHTML=`<span>> ${l[0]}...</span><span>${l[1]}</span>`;
    bootEl.appendChild(ln);
    gsap.to(ln,{opacity:1,duration:.05,delay:.35+idx*.42});
  });
  later(()=>{gsap.to(bootEl,{opacity:0,height:0,marginBottom:0,duration:.5});phaseAssemble();},350+bootLines.length*420+520);
}

function phaseAssemble(){
  const ctx=introCv.getContext('2d');
  const W=introCv.width=innerWidth,H=introCv.height=innerHeight;
  holoWrap.style.opacity=0;
  const r=holoWrap.getBoundingClientRect();
  const off=document.createElement('canvas');off.width=W;off.height=H;
  const ox=off.getContext('2d');ox.fillStyle='#fff';
  const u=r.width/100,X=r.left,Y=r.top,uy=r.height/116;
  ox.beginPath();ox.arc(X+50*u,Y+46*uy,30*u,0,7);ox.fill();
  ox.fillRect(X+34*u,Y+76*uy,32*u,26*uy);
  const img=ox.getImageData(0,0,W,H).data;
  const gap=5,targets=[];
  for(let y=0;y<H;y+=gap)for(let x=0;x<W;x+=gap)if(img[(y*W+x)*4+3]>128)targets.push({x,y});
  while(targets.length>1400)targets.splice(Math.floor(Math.random()*targets.length),1);
  const ps=targets.map(t=>({x:W/2+(Math.random()-.5)*W*1.3,y:H/2+(Math.random()-.5)*H*1.3,tx:t.x,ty:t.y,r:Math.random()*1.4+.7}));
  let t0=null;const DUR=1800;
  function frame(ts){
    if(introDone)return;
    if(!t0)t0=ts;
    const p=Math.min((ts-t0)/DUR,1),e=1-Math.pow(1-p,4);
    ctx.clearRect(0,0,W,H);ctx.fillStyle='#3DE8FF';
    for(const pt of ps){ctx.globalAlpha=.3+.7*e;ctx.fillRect(pt.x+(pt.tx-pt.x)*e,pt.y+(pt.ty-pt.y)*e,pt.r,pt.r);}
    ctx.globalAlpha=1;
    if(p<1)requestAnimationFrame(frame);
    else{
      holoWrap.classList.add('flicker');
      gsap.to(holoWrap,{opacity:1,duration:.15});
      gsap.to(introCv,{opacity:0,duration:.7,delay:.2,onComplete(){ctx.clearRect(0,0,W,H);introCv.style.opacity=1;}});
      later(()=>{holoWrap.classList.remove('flicker');holoWrap.classList.add('assembled');
        gsap.to(consoleEl,{opacity:1,y:0,duration:.7,ease:'power3.out'});phaseDialogue();},900);
    }
  }
  requestAnimationFrame(frame);
}

const SCRIPT=@json($introScript);
function typeLine(html,isHTML,cb){
  if(sayNow.textContent.trim()){
    const prev=document.createElement('div');prev.textContent='» '+sayNow.textContent;sayPrev.prepend(prev);
    while(sayPrev.children.length>2)sayPrev.lastChild.remove();
  }
  sayNow.innerHTML='';
  const plain=isHTML?html.replace(/<[^>]+>/g,''):html;
  let i=0;
  const caret=document.createElement('span');caret.className='tcaret';
  function tick(){
    if(introDone)return;
    sayNow.textContent=plain.slice(0,++i);
    sayNow.appendChild(caret);
    if(i<plain.length)setTimeout(tick,22+Math.random()*20);
    else{if(isHTML){sayNow.innerHTML=html;sayNow.appendChild(caret);}cb&&cb();}
  }
  tick();
}
function visitorScan(){
  const beam=document.getElementById('scanBeam');
  gsap.set(beam,{top:'-12%',opacity:1});
  gsap.to(beam,{top:'104%',duration:1.7,ease:'power1.inOut',onComplete(){gsap.to(beam,{opacity:0,duration:.3});}});
  const ring=document.getElementById('scanRing');
  gsap.fromTo(ring,{opacity:0,scale:.5},{opacity:1,scale:1.25,duration:.8,ease:'power2.out',yoyo:true,repeat:1});
}
function phaseDialogue(){
  let i=0;
  function next(){
    if(introDone||i>=SCRIPT.length)return;
    const step=SCRIPT[i++];
    if(step.diag){
      const d=document.createElement('div');d.className='d'+(step.exc?' exc':'');
      d.innerHTML=`<span>${step.diag}</span><b>${step.ok}</b>`;
      diagEl.appendChild(d);
      gsap.to(d,{opacity:1,duration:.4});
      const r=d.getBoundingClientRect();
      burstAt(r.right-30,r.top+r.height/2,step.exc?['#FFC56E','#FFE3AE']:['#54F0A8','#3DE8FF'],step.exc?26:12);
      if(step.exc)gsap.fromTo(holoWrap,{scale:1},{scale:1.07,duration:.3,yoyo:true,repeat:1});
      later(next,step.d);
      return;
    }
    if(step.scan)visitorScan();
    if(step.wave)gsap.fromTo('#holoArm',{rotate:0},{rotate:-26,duration:.3,yoyo:true,repeat:3,ease:'sine.inOut'});
    let advanced=false;
    const adv=()=>{if(advanced||introDone)return;advanced=true;holoTalkStop();if(step.launch)phaseLaunch();else next();};
    typeLine(step.t,!!step.html);
    holoTalkStart();                        // robot visibly "talks" for every line
    if(voiceOn){
      // advance only after the robot finishes speaking the line (so nothing is cut off)
      speak(step.t,{intro:true,onend:()=>{holoTalkStop();later(adv,420);}});
      later(adv,(step.t.length*95)+6000);   // safety backstop if the voice stalls
    }else{
      later(()=>{holoTalkStop();adv();},step.d);   // voice disabled — just time the lines
    }
  }
  next();
}

function phaseLaunch(){
  gsap.to(holoWrap,{x:isMobile?0:-innerWidth*.28,scale:.66,y:isMobile?-60:40,duration:1,ease:'power3.inOut'});
  gsap.to([consoleEl,diagEl],{opacity:0,y:20,duration:.6});
  const ctx=nameCv.getContext('2d');
  const W=nameCv.width=innerWidth,H=nameCv.height=innerHeight;
  const off=document.createElement('canvas');off.width=W;off.height=H;
  const ox=off.getContext('2d');ox.fillStyle='#fff';
  const small=W<700,fs=Math.min(W*.115,150);
  ox.font=`700 ${fs}px 'Space Grotesk', sans-serif`;ox.textAlign='center';ox.textBaseline='middle';
  if(small){ox.fillText(SUBJ_FIRST,W/2,H/2-fs*.62);ox.fillText(SUBJ_LAST,W/2,H/2+fs*.62);}
  else ox.fillText(SUBJ_FULL,W/2,H/2);
  const img=ox.getImageData(0,0,W,H).data;
  const gap=small?5:4,targets=[];
  for(let y=0;y<H;y+=gap)for(let x=0;x<W;x+=gap)if(img[(y*W+x)*4+3]>128)targets.push({x,y});
  while(targets.length>2600)targets.splice(Math.floor(Math.random()*targets.length),1);
  const ps=targets.map(t=>({x:W/2+(Math.random()-.5)*W*1.4,y:H/2+(Math.random()-.5)*H*1.4,tx:t.x,ty:t.y,
    c:Math.random()<.18?'#3DE8FF':(Math.random()<.5?'#9D6BFF':'#E9EEFB'),r:Math.random()*1.3+.6}));
  // crisp, readable name stamped on top once the particles settle — proper title card
  function drawName(alpha){
    ctx.save();
    ctx.globalAlpha=alpha;
    ctx.textAlign='center';ctx.textBaseline='middle';
    const cy=H/2, grad=ctx.createLinearGradient(W*.22,0,W*.78,0);
    grad.addColorStop(0,'#3DE8FF');grad.addColorStop(.5,'#7FA8FF');grad.addColorStop(1,'#9D6BFF');
    ctx.fillStyle=grad;ctx.shadowColor='rgba(77,124,254,.55)';ctx.shadowBlur=34;
    ctx.font=`700 ${fs}px 'Space Grotesk', sans-serif`;
    let bottom;
    if(small){ctx.fillText(SUBJ_FIRST,W/2,cy-fs*.62);ctx.fillText(SUBJ_LAST,W/2,cy+fs*.62);bottom=cy+fs*1.12;}
    else{ctx.fillText(SUBJ_FULL,W/2,cy);bottom=cy+fs*.62;}
    // glowing accent rule under the name
    ctx.shadowBlur=14;ctx.shadowColor='rgba(61,232,255,.8)';
    ctx.strokeStyle='rgba(61,232,255,'+(.85*alpha)+')';ctx.lineWidth=2;
    const lw=Math.min(W*.34,fs*4.2);
    ctx.beginPath();ctx.moveTo(W/2-lw/2,bottom+fs*.22);ctx.lineTo(W/2+lw/2,bottom+fs*.22);ctx.stroke();
    // role caption
    if(SUBJ_ROLE){
      ctx.shadowBlur=0;ctx.fillStyle='rgba(140,151,180,'+alpha+')';
      const cap=small?11:Math.max(fs*.16,12);
      ctx.font=`600 ${cap}px 'JetBrains Mono', ui-monospace, monospace`;
      ctx.fillText(SUBJ_ROLE.split('').join(' '),W/2,bottom+fs*.22+cap*1.7);
    }
    ctx.restore();
  }
  let t0=null;const DUR=2100;
  function frame(ts){
    if(introDone)return;
    if(!t0)t0=ts;
    const p=Math.min((ts-t0)/DUR,1),e=1-Math.pow(1-p,4);
    ctx.clearRect(0,0,W,H);
    for(const pt of ps){ctx.globalAlpha=.35+.65*e;ctx.fillStyle=pt.c;ctx.fillRect(pt.x+(pt.tx-pt.x)*e,pt.y+(pt.ty-pt.y)*e,pt.r,pt.r);}
    ctx.globalAlpha=1;
    if(e>.72)drawName(Math.min((e-.72)/.28,1));   // resolve dots into a clean, legible name
    if(p<1)requestAnimationFrame(frame);
  }
  requestAnimationFrame(frame);
  later(()=>{
    startWarp();
    // command-center activation: expanding core rings
    const ring=document.getElementById('coreRing');
    gsap.fromTo(ring,{opacity:1,scale:.2},{scale:26,opacity:0,duration:1.9,ease:'power2.in'});
    gsap.to('#introStage',{scale:1.18,duration:2.3,ease:'power2.in'});
    gsap.to(nameCv,{scale:1.25,duration:2.3,ease:'power2.in',transformOrigin:'50% 50%'});
    gsap.fromTo(intro,{x:0},{x:'+=5',duration:.06,yoyo:true,repeat:24,ease:'none',delay:.5});
    later(()=>{gsap.to('#flash',{opacity:1,duration:.35,ease:'power2.in',onComplete(){endIntro(false);}});},1950);
  },DUR+650);
}
let warpOn=false;
function startWarp(){
  warpOn=true;
  const ctx=warpCv.getContext('2d');
  const W=warpCv.width=innerWidth,H=warpCv.height=innerHeight;
  const cx=W/2,cy=H/2,streaks=[];
  for(let i=0;i<160;i++){const a=Math.random()*6.28;streaks.push({a,d:Math.random()*60+10,v:Math.random()*14+8,w:Math.random()*1.6+.6});}
  (function f(){
    if(!warpOn)return;
    ctx.fillStyle='rgba(2,3,8,.3)';ctx.fillRect(0,0,W,H);
    for(const s of streaks){
      s.d+=s.v;s.v*=1.03;
      if(s.d>Math.max(W,H)){s.d=Math.random()*40+10;s.v=Math.random()*14+8;}
      const x1=cx+Math.cos(s.a)*s.d,y1=cy+Math.sin(s.a)*s.d;
      const x2=cx+Math.cos(s.a)*(s.d+s.v*2.4),y2=cy+Math.sin(s.a)*(s.d+s.v*2.4);
      ctx.strokeStyle='rgba(140,200,255,.55)';ctx.lineWidth=s.w;
      ctx.beginPath();ctx.moveTo(x1,y1);ctx.lineTo(x2,y2);ctx.stroke();
    }
    requestAnimationFrame(f);
  })();
}
function endIntro(instant){
  if(introDone)return;
  introDone=true;warpOn=false;
  stopSpeak();
  introTimers.forEach(clearTimeout);
  if(instant||reduced||!hasGSAP){
    intro.remove();
    if(!hasGSAP||reduced){
      document.documentElement.classList.add('no-anim');
      document.getElementById('hud').style.cssText+=';opacity:1;translate:0 0';
      document.getElementById('waypoints').style.opacity=1;
      document.querySelectorAll('[data-hline]').forEach(el=>el.style.transform='none');
      botShow(true);
    } else siteIntro();
    return;
  }
  gsap.to(intro,{opacity:0,duration:.8,ease:'power2.out',delay:.15,onComplete(){intro.remove();siteIntro();}});
}
document.getElementById('skipIntro').addEventListener('click',()=>endIntro(false));
if(hasGSAP)gsap.set(consoleEl,{y:18});

/* Boot the intro. If voice is wanted we gate behind an ENTER button — that one
   click unlocks browser audio so the whole intro narrates itself automatically. */
const enterGate=document.getElementById('enterGate');
const enterBtn=document.getElementById('enterBtn');
function removeGate(){
  if(!enterGate)return;
  if(hasGSAP&&!reduced)gsap.to(enterGate,{opacity:0,duration:.4,onComplete(){enterGate.remove();}});
  else enterGate.remove();
}
function startBoot(unlock){
  if(unlock){
    RobotSfx.unlock();meSpeakPrime();
    if(canSpeak){try{const u=new SpeechSynthesisUtterance(' ');u.volume=0;speechSynthesis.speak(u);}catch(e){}}
  }
  removeGate();
  phaseBoot();
}
if(reduced||!hasGSAP){ removeGate(); endIntro(true); }
else if(voiceOn&&enterBtn){ enterBtn.addEventListener('click',()=>startBoot(true),{once:true}); }
else { removeGate(); startBoot(false); }

function siteIntro(){
  const tl=gsap.timeline({defaults:{ease:'power4.out'}});
  tl.to('#hud',{opacity:1,translate:'0 0',duration:.9},0)
    .to('#waypoints',{opacity:1,duration:.8},.2)
    .from('[data-hline]',{yPercent:118,duration:1.2,stagger:.14},.1)
    .from('.holo-chip',{opacity:0,y:24,duration:.9,stagger:.1},.6)
    .add(()=>botShow(false),.9);
}

/* =================== LENIS =================== */
let lenis=null;
if(!reduced&&typeof Lenis!=='undefined'&&hasGSAP){
  lenis=new Lenis({duration:1.2});
  lenis.on('scroll',ScrollTrigger.update);
  gsap.ticker.add(t=>lenis.raf(t*1000));
  gsap.ticker.lagSmoothing(0);
}
document.querySelectorAll('a[href^="#"]').forEach(a=>{
  a.addEventListener('click',e=>{
    const el=document.querySelector(a.getAttribute('href'));
    if(!el)return;e.preventDefault();
    if(lenis)lenis.scrollTo(el,{offset:-60});
    else el.scrollIntoView({behavior:reduced?'auto':'smooth'});
  });
});

/* HUD + waypoints */
const sectors=[...document.querySelectorAll('main > .module')];
const wps=[...document.querySelectorAll('.wp')];
const sectorNames=@json($sectorNames);
const hudFill=document.getElementById('hudFill'),hudPct=document.getElementById('hudPct'),hudSector=document.getElementById('hudSector');
function onScrollUpdate(){
  const max=document.documentElement.scrollHeight-innerHeight;
  const p=max>0?Math.min(scrollY/max,1):0;
  hudFill.style.width=(p*100)+'%';hudPct.textContent=Math.round(p*100)+'%';
}
addEventListener('scroll',onScrollUpdate,{passive:true});onScrollUpdate();
const io=new IntersectionObserver(entries=>{
  entries.forEach(en=>{
    if(!en.isIntersecting)return;
    const idx=sectors.indexOf(en.target);if(idx<0)return;
    wps.forEach((w,i)=>w.classList.toggle('active',i===idx));
    hudSector.textContent=sectorNames[idx];
    botSay(idx);
  });
},{threshold:.32});
sectors.forEach(s=>io.observe(s));

/* reveals */
if(hasGSAP&&!reduced){
  gsap.utils.toArray('[data-reveal]').forEach(el=>{
    gsap.to(el,{opacity:1,y:0,duration:1.05,ease:'power4.out',scrollTrigger:{trigger:el,start:'top 86%',once:true}});
  });
  gsap.to('#logFill',{scaleY:1,ease:'none',scrollTrigger:{trigger:'.logs',start:'top 75%',end:'bottom 60%',scrub:.6}});
  gsap.utils.toArray('[data-float]').forEach((el,i)=>{
    gsap.to(el,{y:'+='+(10+i*4),duration:2.6+i*.4,yoyo:true,repeat:-1,ease:'sine.inOut'});
  });
}else{
  document.documentElement.classList.add('no-anim');
  const f=document.getElementById('logFill');if(f)f.style.transform='scaleY(1)';
}

/* cursor / magnetic / tilt / mouse parallax */
if(fine&&!reduced&&hasGSAP){
  const dot=document.getElementById('curDot'),ring=document.getElementById('curRing');
  const dx=gsap.quickTo(dot,'x',{duration:.08}),dy=gsap.quickTo(dot,'y',{duration:.08});
  const rx=gsap.quickTo(ring,'x',{duration:.42,ease:'power3'}),ry=gsap.quickTo(ring,'y',{duration:.42,ease:'power3'});
  addEventListener('pointermove',e=>{dx(e.clientX);dy(e.clientY);rx(e.clientX);ry(e.clientY);},{passive:true});
  document.querySelectorAll('a,button,.glass,#bot').forEach(el=>{
    el.addEventListener('pointerenter',()=>ring.classList.add('hov'));
    el.addEventListener('pointerleave',()=>ring.classList.remove('hov'));
  });
  addEventListener('pointermove',e=>{
    const nx2=e.clientX/innerWidth-.5,ny2=e.clientY/innerHeight-.5;
    document.querySelectorAll('.holo-chip').forEach((el,i)=>{
      gsap.to(el,{x:nx2*(14+i*6),duration:1,ease:'power2.out'});
    });
  },{passive:true});
}else{document.getElementById('curDot').remove();document.getElementById('curRing').remove();}
if(fine&&!reduced&&hasGSAP){
  document.querySelectorAll('[data-magnetic]').forEach(el=>{
    const mx=gsap.quickTo(el,'x',{duration:.4,ease:'power3'}),my=gsap.quickTo(el,'y',{duration:.4,ease:'power3'});
    el.addEventListener('pointermove',e=>{const r=el.getBoundingClientRect();
      mx((e.clientX-(r.left+r.width/2))*.3);my((e.clientY-(r.top+r.height/2))*.3);});
    el.addEventListener('pointerleave',()=>{mx(0);my(0);});
  });
}
document.querySelectorAll('.glass').forEach(card=>{
  card.addEventListener('pointermove',e=>{
    const r=card.getBoundingClientRect();
    const px=(e.clientX-r.left)/r.width,py=(e.clientY-r.top)/r.height;
    card.style.setProperty('--mx',(px*100)+'%');card.style.setProperty('--my',(py*100)+'%');
    if(fine&&!reduced&&hasGSAP&&card.hasAttribute('data-tilt'))
      gsap.to(card,{rotateY:(px-.5)*5,rotateX:(.5-py)*5,transformPerspective:900,duration:.5,ease:'power2.out'});
  });
  card.addEventListener('pointerleave',()=>{
    if(hasGSAP&&card.hasAttribute('data-tilt'))gsap.to(card,{rotateX:0,rotateY:0,duration:.8,ease:'elastic.out(1,.5)'});
  });
});

/* =================== NEURAL LINKS BETWEEN SKILL MODULES =================== */
const neural=document.getElementById('neural'),nlx=neural.getContext('2d');
const nodes=[...document.querySelectorAll('[data-node]')];
const NLINKS=[[0,1],[0,2],[0,3],[0,4],[1,4],[2,3]];
function neuralFrame(){
  const wrapEl=neural.parentElement;
  const r=wrapEl.getBoundingClientRect();
  neural.width=r.width;neural.height=r.height;
  nlx.clearRect(0,0,r.width,r.height);
  const t=performance.now()/1000;
  NLINKS.forEach(([a,b],i)=>{
    if(!nodes[a]||!nodes[b])return;
    const ra=nodes[a].getBoundingClientRect(),rb=nodes[b].getBoundingClientRect();
    const ax=ra.left-r.left+ra.width/2,ay=ra.top-r.top+ra.height/2;
    const bx3=rb.left-r.left+rb.width/2,by3=rb.top-r.top+rb.height/2;
    nlx.strokeStyle=`rgba(110,180,255,${.1+.08*Math.sin(t*2+i)})`;nlx.lineWidth=1;
    nlx.beginPath();nlx.moveTo(ax,ay);nlx.lineTo(bx3,by3);nlx.stroke();
    const tp=(t*.22+i*.16)%1;
    nlx.fillStyle='rgba(61,232,255,.7)';
    nlx.beginPath();nlx.arc(ax+(bx3-ax)*tp,ay+(by3-ay)*tp,1.8,0,7);nlx.fill();
  });
  if(!reduced)requestAnimationFrame(neuralFrame);
}
neuralFrame();if(reduced)setInterval(neuralFrame,500);

/* energy bars + counters */
document.querySelectorAll('[data-bar]').forEach(bar=>{
  const pct=+bar.dataset.bar;
  const num=bar.closest('.mod-row').querySelector('[data-en]');
  if(!hasGSAP||reduced){bar.style.width=pct+'%';num.textContent=pct+'%';return;}
  ScrollTrigger.create({trigger:bar,start:'top 92%',once:true,
    onEnter(){
      gsap.to(bar,{width:pct+'%',duration:1.4,ease:'power3.out',delay:.1});
      const o={v:0};
      gsap.to(o,{v:pct,duration:1.4,ease:'power3.out',delay:.1,onUpdate(){num.textContent=Math.round(o.v)+'%';}});
    }});
});
document.querySelectorAll('[data-count]').forEach(el=>{
  const end=+el.dataset.count;
  if(!hasGSAP||reduced){el.textContent=end;return;}
  const o={v:0};
  ScrollTrigger.create({trigger:el,start:'top 88%',once:true,
    onEnter(){gsap.to(o,{v:end,duration:1.5,ease:'power3.out',onUpdate(){el.textContent=Math.round(o.v);}});}});
});
document.querySelectorAll('[data-ach]').forEach((b,i)=>{
  if(!hasGSAP||reduced)return;
  const st=b.querySelector('.st');
  ScrollTrigger.create({trigger:b,start:'top 85%',once:true,
    onEnter(){
      gsap.from(b,{scale:.85,opacity:0,duration:.7,delay:i*.12,ease:'back.out(2)'});
      gsap.to(st,{opacity:1,duration:.5,delay:i*.12+.55});
      const r=b.getBoundingClientRect();
      setTimeout(()=>burstAt(r.left+r.width/2,r.top+30,['#FFC56E','#FFE3AE','#3DE8FF'],22),i*120+400);
      if(i===3)botCelebrate();
    }});
});

/* =================== ASSISTANT =================== */
const bot=document.getElementById('bot'),bubble=document.getElementById('bubble');
const pupils=document.getElementById('pupils');
const SAY=@json(array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $settings['assistant_lines'])))));
let lastSaid=-1,bubbleTimer=null,botVisible=false,idleTimer=null;
function botShow(instant){
  botVisible=true;
  if(hasGSAP&&!reduced&&!instant){
    gsap.to(bot,{opacity:1,duration:.6});
    gsap.from('#botBody',{y:60,scale:1.6,duration:.9,ease:'back.out(1.6)'});
  }else bot.style.opacity=1;
  botSay(0,true);resetIdle();
}
function botSay(idx,force){
  if(!botVisible||(idx===lastSaid&&!force))return;
  lastSaid=idx;botFreeSay(SAY[idx]||'');
}
function botFreeSay(html){
  if(!botVisible||!html)return;
  const plain=html.replace(/<[^>]+>/g,'');
  bubble.innerHTML=html;bubble.classList.add('show');
  speak(plain,{bot:true});
  botMouthStart();
  clearTimeout(bubbleTimer);
  bubbleTimer=setTimeout(()=>{bubble.classList.remove('show');botMouthStop();},5200);
  if(hasGSAP&&!reduced)gsap.fromTo('#armWave',{rotate:0},{rotate:-24,duration:.3,yoyo:true,repeat:3,ease:'sine.inOut',transformOrigin:'33px 80px'});
  resetIdle();
}
function botCelebrate(){
  if(!hasGSAP||reduced)return;
  gsap.timeline().to('#botBody',{y:-26,duration:.32,ease:'power2.out'}).to('#botBody',{y:0,duration:.5,ease:'bounce.out'});
  const r=bot.getBoundingClientRect();
  burstAt(r.left+r.width/2,r.top+20,['#3DE8FF','#FFC56E','#9D6BFF','#54F0A8'],40);
}
const IDLE_TIPS=@json(array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $settings['assistant_idle'])))));
function resetIdle(){
  clearTimeout(idleTimer);
  idleTimer=setTimeout(()=>{if(botVisible)botFreeSay(IDLE_TIPS[Math.floor(Math.random()*IDLE_TIPS.length)]);},26000);
}
['scroll','pointerdown','keydown'].forEach(ev=>addEventListener(ev,resetIdle,{passive:true}));
if(fine&&!reduced){
  addEventListener('pointermove',e=>{
    const r=bot.getBoundingClientRect();
    const dx2=(e.clientX-(r.left+r.width/2))/innerWidth,dy2=(e.clientY-(r.top+40))/innerHeight;
    pupils.style.transform=`translate(${dx2*7}px, ${dy2*6}px)`;
  },{passive:true});
}
bot.addEventListener('click',()=>{botCelebrate();botFreeSay(@json($settings['assistant_click']));});
document.getElementById('cName').addEventListener('focus',()=>botFreeSay(@json($settings['assistant_focus'])),{once:true});

/* resume */
document.getElementById('resumeBtn').addEventListener('click',function(e){
  burstAt(e.clientX||innerWidth/2,e.clientY||innerHeight/2,['#3DE8FF','#54F0A8'],22);
  botFreeSay(@json($settings['assistant_resume']));
});

/* contact */
const form=document.getElementById('cmdForm');
const complete=document.getElementById('complete');
form.addEventListener('submit',async e=>{
  e.preventDefault();
  if(!form.checkValidity()){form.reportValidity();return;}
  const btn=form.querySelector('.launch');
  try{
    const res=await fetch(CONTACT_URL,{
      method:'POST',
      headers:{'Content-Type':'application/json','Accept':'application/json',
        'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content},
      body:JSON.stringify({name:form.name.value,email:form.email.value,message:form.message.value})
    });
    if(!res.ok)throw 0;
    btn.classList.add('sent');
    burstAt(innerWidth/2,innerHeight/2,['#54F0A8','#3DE8FF','#FFC56E','#9D6BFF'],90);
    botCelebrate();
    setTimeout(()=>complete.classList.add('show'),700);
  }catch(err){
    botFreeSay("Transmission failed — signal interference. Please try again, commander. 📡");
  }
});
document.getElementById('completeClose').addEventListener('click',()=>{
  complete.classList.remove('show');
  form.reset();
  form.querySelector('.launch').classList.remove('sent');
});
})();
</script>
</body>
</html>
