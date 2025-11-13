# 🎯 Referral System - Quick Reference Card

## 📍 Admin URLs

| Page | URL | Purpose |
|------|-----|---------|
| Analytics Dashboard | `/admin/referrals` | Overview, charts, leaderboard |
| All Referrals | `/admin/referrals/list` | Filterable list of referrals |
| Referral Detail | `/admin/referrals/show/{id}` | Single referral information |
| Code Management | `/admin/referrals/codes` | Manage referral codes |
| Export Data | `/admin/referrals/export` | CSV/Excel export |

## 📍 Client URLs

| Page | URL | Purpose |
|------|-----|---------|
| Referral Dashboard | `/client/referrals/dashboard` | View stats and referrals |
| Share Page | `/client/referrals/share` | Social sharing options |
| Referral History | `/client/referrals/history` | Past referrals |
| Registration with Code | `/register?ref=CODE` | Signup with referral |

## 🎁 Reward Structure

### Referred User (Immediate)
- **500 points** on signup
- **15% off coupon** (30 days validity)

### Referrer (After First Payment)
- **1,000 points** on completion
- **20% off coupon** (60 days validity)

## 🔑 Key Features

### Client Features:
✅ Unique referral code generation  
✅ Social media sharing (6 platforms)  
✅ Email invitations  
✅ Copy-to-clipboard  
✅ Real-time statistics  
✅ Pending/completed tracking  
✅ Welcome bonuses  

### Admin Features:
✅ Analytics dashboard with charts  
✅ Filter by status/date/user  
✅ Search functionality  
✅ Manual referral processing  
✅ Code activation/deactivation  
✅ CSV/Excel export  
✅ Top performers leaderboard  
✅ Conversion funnel visualization  

## 🎨 Status Colors

- 🟡 **Yellow** = Pending (awaiting first payment)
- 🔵 **Blue** = Completed (payment made, pending reward)
- 🟢 **Green** = Rewarded (rewards distributed)
- 🔴 **Red** = Inactive/Error

## 📊 Important Metrics

1. **Conversion Rate** = Successful ÷ Total × 100
2. **Drop-off Rate** = (1 - Conversion Rate)
3. **Average Referrals** = Total Referrals ÷ Active Users
4. **Total Rewards** = Sum of all points distributed
5. **Code Usage** = Total times code used
6. **Pending Value** = Pending Referrals × 1000 points

## 🗄️ Database Tables

- `referral_codes` - User referral codes
- `referrals` - Individual referral records
- `users` - Extended with referral fields
- `referral_campaigns` - Future promotional campaigns

## 🔧 Quick Actions

### Admin Can:
```php
// Process pending referral manually
POST /admin/referrals/{id}/process

// Toggle code status
PATCH /admin/referrals/codes/{id}/toggle

// Export filtered data
GET /admin/referrals/export?format=csv&status=pending
```

### Client Can:
```php
// Get referral code
GET /client/referrals/code

// Validate code (AJAX)
POST /client/referrals/validate

// Send email invitation
POST /client/referrals/invite
```

## 📧 Email Templates

1. **Referral Signup** - Sent to referrer when someone signs up
2. **Referral Completed** - Sent to referrer when payment made

## 🛠️ Configuration File

**Location:** `config/referral.php`

**Key Settings:**
```php
'rewards.referrer.completion_points' => 1000
'rewards.referred.welcome_points' => 500
'rewards.referrer.coupon_discount' => 20
'rewards.referred.coupon_discount' => 15
```

## 🧪 Testing Commands

```bash
# Run migrations
php artisan migrate

# Check referral records
php artisan tinker
>>> App\Models\Referral::with('referrer','referred')->get()

# Check referral codes
>>> App\Models\ReferralCode::with('user')->get()

# Test email queue
php artisan queue:work
```

## 📈 SQL Quick Queries

```sql
-- Total referrals today
SELECT COUNT(*) FROM referrals WHERE DATE(created_at) = CURDATE();

-- Pending referrals
SELECT COUNT(*) FROM referrals WHERE status = 'pending';

-- Top referrer
SELECT u.fullName, COUNT(*) as total 
FROM referrals r 
JOIN users u ON r.referrer_id = u.id 
GROUP BY u.id 
ORDER BY total DESC 
LIMIT 1;

-- Conversion rate
SELECT 
  COUNT(*) as total,
  SUM(CASE WHEN status = 'rewarded' THEN 1 ELSE 0 END) as successful,
  (SUM(CASE WHEN status = 'rewarded' THEN 1 ELSE 0 END) * 100.0 / COUNT(*)) as rate
FROM referrals;
```

## 🚨 Troubleshooting

### Issue: Code validation not working
**Check:** CSRF token, route exists, JavaScript console

### Issue: Rewards not credited
**Check:** Payment status, queue worker, logs

### Issue: Email not sent
**Check:** Mail config, queue worker, failed_jobs table

### Issue: Charts not loading
**Check:** Chart.js CDN, browser console, data format

## 📞 Support Files

- `REFERRAL_SYSTEM_COMPLETE.md` - Full documentation
- `REFERRAL_SYSTEM_INTEGRATION_COMPLETE.md` - Integration details
- `REFERRAL_SYSTEM_TESTING_GUIDE.md` - Testing scenarios
- `REFERRAL_SYSTEM_IMPLEMENTATION_PLAN.md` - Original plan

## 🎯 Success Metrics to Track

1. Monthly referral signups
2. Conversion rate percentage
3. Average days to first payment
4. Total rewards distributed
5. Most effective referrers
6. Code usage patterns
7. Drop-off points

## ⚡ Performance Tips

- Use database indexes
- Enable query caching
- Queue email notifications
- Lazy load relationships
- Paginate large datasets
- Monitor slow queries

## 🔐 Security Features

✅ CSRF protection  
✅ Self-referral prevention  
✅ Duplicate referral check  
✅ Code validation  
✅ Database constraints  
✅ IP tracking  
✅ User agent logging  

---

**Quick Access URLs:**
- Admin Dashboard: `/admin/referrals`
- Client Dashboard: `/client/referrals/dashboard`
- Registration: `/register?ref=CODE`

**Need Help?** Check the full documentation files listed above.
