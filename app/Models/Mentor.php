<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Hash;

/**
 * App\Models\Mentor
 *
 * @property int $mentor_srl
 * @property string $id
 * @property string $name
 * @property string $password
 * @property string|null $profile_image
 * @property string $introduce
 * @property string $address
 * @property string|null $farm_name
 * @property string|null $phone
 * @property string|null $career
 * @property string|null $crops
 * @property string|null $sex
 * @property int|null $mentoring_count
 * @property int|null $homi
 * @property string|null $birthday
 * @property \Illuminate\Support\Carbon $regdate
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MentorDiary[] $diaries
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mentor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mentor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mentor query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mentor whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mentor whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mentor whereCareer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mentor whereCrops($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mentor whereFarmName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mentor whereHomi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mentor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mentor whereIntroduce($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mentor whereMentorSrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mentor whereMentoringCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mentor whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mentor wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mentor wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mentor whereProfileImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mentor whereRegdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mentor whereSex($value)
 * @mixin \Eloquent
 */
class Mentor extends Model implements JWTSubject
{
    use Notifiable;

    /**
     *
     */
    const CREATED_AT = 'regdate';
    /**
     *
     */
    const UPDATED_AT = null;

    /**
     * @var string
     */
    protected $table = "cp_mentors";
    /**
     * @var string
     */
    protected $primaryKey = "mentor_srl";
    /**
     * @var array
     */
    protected $guarded = []; // name을 제외한 모든 속성들은 대량 할당이 가능하다.
//    protected $fillable = ['name']; // name, 를 대량 할당이 가능하다.
//  guarded 혹은 fillable 둘 중에 하나만 써야 함.

    /**
     * @var array
     */
    protected $hidden = ['password', 'phone', 'regdate'];

    /** mutators */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * @param $value
     */
    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = encrypt($value);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function diaries()
    {
        return $this->hasMany(MentorDiary::class, 'mentor_srl');
    }


    /**
     * @param $value
     * @return string
     */
    public function getProfileImageAttribute($value)
    {
        return empty($value) ? $value = "/images/ico/homi_bg.png" : config('nclound.ncloud_object_storage_host')."/".$value;
    }

    /**
     * @param $value
     */
    public function setProfileImageAttribute($value)
    {
        if ($value !== "File not allowed") {
            $this->attributes['profile_image'] = $value;
        }
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        // TODO: Implement getJWTIdentifier() method.
        return $this->getKey();

    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {

        return [
            'user_type' => 'mentor',
            'id' => $this->mentor_srl,
            'profile_image' => $this->profile_image,
        ];
    }
}
