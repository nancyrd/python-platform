<x-app-layout>
    

    <style>
  :root{
    --primary-purple:#7B2CBF;
    --secondary-purple:#9D4EDD;
    --accent-purple:#5A189A;
    --panel:#ffffff;
    --bg:#fafafa;
    --border:#d2b7f0;
    --ink:#222;
    --muted:#666;
  }
  body{ background: var(--bg); }

  .hc-wrap{
    max-width: 960px;
    margin: 2rem auto;
    padding: 1rem;
  }

  .hc-hero{
    text-align:center;
    margin: 2rem 0 1.5rem;
  }
  .hc-title{
    color: var(--accent-purple);
    font-size: clamp(1.75rem, 1.2rem + 2vw, 2.25rem);
    margin: 0 0 .5rem;
    font-weight: 800;
  }
  .hc-sub{ color: var(--muted); margin: 0; }

  .hc-grid{
    display:grid;
    grid-template-columns: 1fr;
    gap: 24px;
  }
  @media (min-width: 960px){ .hc-grid{ grid-template-columns: 1fr 1fr; } }

  /* Panel */
  .panel{
    background: var(--panel);
    border: 2px solid var(--border);
    border-radius: 18px;
    box-shadow: 0 0 10px rgba(0,0,0,.05);
    padding: 1.25rem;
  }

  /* FAQ */
  .faq-head{ display:flex; align-items:center; gap:10px; margin-bottom:.5rem; }
  .faq-head h2{ margin:0; color:var(--primary-purple); font-size:1.25rem; }
  details{ border:1px solid var(--border); border-radius:14px; background:#f7f4fe; padding:.75rem 1rem; }
  details + details{ margin-top:.75rem; }
  summary{
    cursor:pointer; outline:none; list-style:none;
    font-weight:700; color:var(--ink);
  }
  summary::-webkit-details-marker{ display:none; }
  .faq-content{ color:var(--muted); margin-top:.5rem; }

  /* Contact */
  .form-group{ margin-bottom: .9rem; }
  label{ display:block; font-weight:700; margin-bottom:.4rem; color:var(--ink); }
  .form-control{
    width:100%; padding: .9rem 1rem;
    border:1px solid var(--border); border-radius:14px;
    background:#fff; color:var(--ink);
    transition: box-shadow .2s, border-color .2s;
  }
  .form-control:focus{
    outline:none; border-color: var(--secondary-purple);
    box-shadow:0 0 0 4px rgba(157,78,221,.15);
  }
  textarea.form-control{ min-height: 140px; resize: vertical; }

  .error-text{ color:#F44336; font-size:.9rem; margin-top:.35rem; }
  .flash-success{
    margin-bottom:1rem;padding:.8rem 1rem;border:1px solid #c7efcf;
    background:#eafff0;color:#175d2b;border-radius:12px;
  }

  .btn{
    border:none; cursor:pointer; font-weight:800; border-radius:999px;
    padding:.9rem 1.6rem; transition: transform .15s, box-shadow .2s, background .2s;
  }
  .btn-primary{ background: var(--secondary-purple); color:#fff; }
  .btn-primary:hover{ background:var(--accent-purple); transform:translateY(-1px); box-shadow:0 8px 18px rgba(90,24,154,.3); }
</style>


  <div class="hc-wrap">
    <div class="hc-hero">
      <h1 class="hc-title">Help Center</h1>
      <p class="hc-sub">Find quick answers below, or reach out to our support team.</p>
    </div>

    @if (session('status'))
      <div class="flash-success">{{ session('status') }}</div>
    @endif

    <div class="hc-grid">
      {{-- FAQ Panel --}}
      <section class="panel">
        <div class="faq-head">
          <h2>FAQs</h2>
        </div>

        <details>
          <summary>How do I unlock new levels?</summary>
          <div class="faq-content">
            Pass the pre-assessment with a high score or complete the prior level. Your dashboard shows what’s locked/unlocked.
          </div>
        </details>

        <details>
          <summary>I forgot my password. What should I do?</summary>
          <div class="faq-content">
            Use the “Forgot Password” link on the login page. We’ll email you a reset link. If you don’t see it, check spam.
          </div>
        </details>

        <details>
          <summary>Where can I view my scores and progress?</summary>
          <div class="faq-content">
            Head to your Dashboard. The player stats and stage cards display your latest scores and status.
          </div>
        </details>

        <details>
          <summary>How do I update my profile details?</summary>
          <div class="faq-content">
            Go to Profile (top-right menu), edit your info, and click “Save changes”. Changes apply immediately.
          </div>
        </details>
      </section>

      {{-- Contact Panel --}}
      <section class="panel">
        <h2 style="margin:0 0 .75rem; color:var(--primary-purple); font-size:1.25rem;">Contact Support</h2>

        <form method="POST" action="{{ route('support.submit') }}">
          @csrf

          <div class="form-group">
            <label for="name">Your Name</label>
            <input id="name" name="name" class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name', auth()->user()->name ?? '') }}" placeholder="Your full name">
            @error('name') <div class="error-text">{{ $message }}</div> @enderror
          </div>

          <div class="form-group">
            <label for="email">Email Address</label>
            <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email', auth()->user()->email ?? '') }}" placeholder="you@example.com">
            @error('email') <div class="error-text">{{ $message }}</div> @enderror
          </div>

          <div class="form-group">
            <label for="subject">Subject</label>
            <input id="subject" name="subject" class="form-control @error('subject') is-invalid @enderror"
                   value="{{ old('subject') }}" placeholder="How can we help?">
            @error('subject') <div class="error-text">{{ $message }}</div> @enderror
          </div>

          <div class="form-group">
            <label for="message">Message</label>
            <textarea id="message" name="message" class="form-control @error('message') is-invalid @enderror"
                      placeholder="Describe the issue or question...">{{ old('message') }}</textarea>
            @error('message') <div class="error-text">{{ $message }}</div> @enderror
          </div>

          <button type="submit" class="btn btn-primary">Send message</button>
        </form>
      </section>
    </div>
  </div>
    </x-app-layout>